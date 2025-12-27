<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Departement;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Destination;
use App\Models\Sortie;
use App\Models\Personnel;
use App\Enums\TypeTransport;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    use WithPagination;

    // NAVIGATION
    public $activeTab = 'sorties';

    // CHAMPS COMMUNS
    public $numero_ordre;
    public $date_debut;
    public $date_fin;
    public $chauffeur;
    public $transport;

    // CONTRAINTES
    public $minDate;

    // STANDARD
    public $departement_id;
    public $filiere_id;
    public $module_id;
    public $destination_id;
    public $encadrants_ids = [];
    public $encadrant_to_add;

    // CHAMPS "AJOUT RAPIDE"
    public $is_new_destination = false;
    public $new_destination_nom;

    public $is_new_module = false;
    public $new_module_nom;

    public $is_new_encadrant = false;
    public $new_encadrant_nom;

    // MODES
    public $is_mode_libre = false;
    public $is_mode_reservation = false;
    public $objet;
    public $personnel;

    // SPECIAL (MEDECINE, etc.)
    public $is_special_dept = false;

    // GESTION
    public $filieres = [];
    public $modules = [];
    public $isUpdateMode = false;
    public $sortie_id;

    #[Url] public $selectedMonth;
    #[Url] public $selectedYear;

    protected $listeners = ['deleteSortie', 'refresh' => '$refresh'];

    public function mount() {
        $this->filieres = collect();
        $this->modules = collect();
        if (is_null($this->selectedMonth)) $this->selectedMonth = Carbon::now()->month;
        if (is_null($this->selectedYear)) $this->selectedYear = Carbon::now()->year;
        $this->minDate = Carbon::today()->format('Y-m-d');
    }

    // --- BASCULES POUR AJOUT RAPIDE ---
    public function toggleNewDestination() {
        $this->is_new_destination = !$this->is_new_destination;
        $this->new_destination_nom = null;
        $this->destination_id = null;
    }

    public function toggleNewModule() {
        $this->is_new_module = !$this->is_new_module;
        $this->new_module_nom = null;
        $this->module_id = null;
    }

    public function toggleNewEncadrant() {
        $this->is_new_encadrant = !$this->is_new_encadrant;
        $this->new_encadrant_nom = null;
        $this->encadrant_to_add = null;
    }

    protected function rules() {
        $rules = [
            'numero_ordre' => 'required|integer',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'chauffeur' => 'required|string|max:191',
            'transport' => ['nullable'],
        ];

        if ($this->is_mode_reservation) {
            $rules['departement_id'] = 'required|exists:departements,id';
        }
        elseif ($this->is_mode_libre && empty($this->departement_id)) {
            $rules['objet'] = 'required|string|max:255';
            $rules['personnel'] = 'nullable|string|max:255';
        }
        else {
            $rules['departement_id'] = 'required|exists:departements,id';

            if ($this->is_new_destination) {
                $rules['new_destination_nom'] = 'required|string|min:2|unique:destinations,nom';
            } else {
                $rules['destination_id'] = 'required|exists:destinations,id';
            }

            if (!$this->is_special_dept) {
                $rules['filiere_id'] = 'required|exists:filieres,id';

                if ($this->is_new_module) {
                    $rules['new_module_nom'] = 'required|string|min:2';
                } else {
                    $rules['module_id'] = 'required|exists:modules,id';
                }
                $rules['encadrants_ids'] = 'array';
            } else {
                $rules['objet'] = 'nullable|string|max:255';
                $rules['personnel'] = 'nullable|string|max:255';
            }
        }
        return $rules;
    }

    // NAVIGATION MOIS
    public function previousMonth() {
        $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
    }

    public function nextMonth() {
        $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->addMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
    }

    // --- LOGIQUE INTELLIGENTE DE NUMÉROTATION ---

    private function safeShiftOrders($year, $startFrom) {
        $conflit = Sortie::whereYear('date_debut', $year)
            ->where('numero_ordre', $startFrom)
            ->first();

        if ($conflit) {
            if ($conflit->date_validation) return; // Stop si validé

            // Récursivité pour libérer la place suivante
            $this->safeShiftOrders($year, $startFrom + 1);

            $conflit->numero_ordre = $startFrom + 1;
            $conflit->save();
        }
    }

    private function calculatePositionByDate($dateStr) {
        if (!$dateStr) return 1;
        $year = Carbon::parse($dateStr)->year;

        $maxValidated = Sortie::whereYear('date_debut', $year)
            ->whereNotNull('date_validation')
            ->max('numero_ordre') ?? 0;

        $minAllowed = $maxValidated + 1;

        $nextSortie = Sortie::whereYear('date_debut', $year)
            ->where('date_debut', '>', $dateStr)
            ->orderBy('numero_ordre', 'asc')
            ->first();

        if ($nextSortie) {
            $candidate = $nextSortie->numero_ordre;
        } else {
            $maxAll = Sortie::whereYear('date_debut', $year)->max('numero_ordre');
            $candidate = $maxAll ? $maxAll + 1 : 1;
        }

        if ($candidate < $minAllowed) {
            $candidate = $minAllowed;
        }

        while (true) {
            $occupant = Sortie::whereYear('date_debut', $year)
                ->where('numero_ordre', $candidate)
                ->first();

            if (!$occupant) {
                break;
            }

            if ($occupant->date_validation) {
                $candidate++;
            } else {
                $nextIsLocked = Sortie::whereYear('date_debut', $year)
                    ->where('numero_ordre', $candidate + 1)
                    ->whereNotNull('date_validation')
                    ->exists();

                if ($nextIsLocked) {
                    $candidate += 2;
                } else {
                    break;
                }
            }
        }

        return $candidate;
    }

    public function updatedDateDebut() {
        if ($this->date_debut) {
            $date = Carbon::parse($this->date_debut);
            if ($date->isSunday()) {
                $this->addError('date_debut', 'Le dimanche est bloqué.');
                $this->date_debut = null;
                return;
            }
            if (!$this->isUpdateMode) {
                $this->numero_ordre = $this->calculatePositionByDate($this->date_debut);
            }

            if (!$this->date_fin || $this->date_fin < $this->date_debut) {
                $this->date_fin = $this->date_debut;
            }
        }
    }

    public function switchTab($tab) { $this->activeTab = $tab; }

    public function addEncadrant() {
        if ($this->is_new_encadrant) {
            $this->validate(['new_encadrant_nom' => 'required|string|min:3']);
            $newProf = Personnel::firstOrCreate(
                ['nom' => trim(strtoupper($this->new_encadrant_nom))],
                ['departement_id' => $this->departement_id]
            );
            if (!in_array($newProf->id, $this->encadrants_ids)) {
                $this->encadrants_ids[] = $newProf->id;
            }
            $this->is_new_encadrant = false;
            $this->new_encadrant_nom = null;
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Enseignant ajouté et sélectionné.']);
        } else {
            $this->validate(['encadrant_to_add' => 'required|exists:personnels,id']);
            if (!in_array($this->encadrant_to_add, $this->encadrants_ids)) {
                $this->encadrants_ids[] = (int)$this->encadrant_to_add;
            }
            $this->reset('encadrant_to_add');
        }
    }

    public function removeEncadrant($id) {
        $this->encadrants_ids = array_diff($this->encadrants_ids, [$id]);
        $this->encadrants_ids = array_values($this->encadrants_ids);
    }

    public function updatedDepartementId() {
        $dept = Departement::find($this->departement_id);

        $standard_depts = [
            'BIOLOGIE', 'CHIMIE', 'DROIT', 'GEOLOGIE', 'INFORMATIQUE', 'MATHS', 'MATHEMATIQUES'
        ];

        if ($dept) {
            $deptNameClean = strtoupper(trim($dept->nom));
            $is_standard = false;

            foreach($standard_depts as $std) {
                if (str_contains($deptNameClean, $std)) {
                    $is_standard = true;
                    break;
                }
            }

            if (!$is_standard) {
                $this->is_special_dept = true;
                $this->filieres = collect();
                $this->modules = collect();
                $this->objet = null;
                $this->personnel = null;
            } else {
                $this->is_special_dept = false;
                $allFilieres = Filiere::where('departement_id', $this->departement_id)->get();
                $this->filieres = $allFilieres->sortBy(function ($filiere) {
                    $niveauVal = $filiere->niveau ? $filiere->niveau->value : '';
                    $semestreVal = $filiere->semestre ? $filiere->semestre->value : '';
                    $semNum = (int) filter_var($semestreVal, FILTER_SANITIZE_NUMBER_INT);
                    $isMaster = (stripos($niveauVal, 'Master') !== false);
                    if (!$isMaster) {
                        return '0_' . str_pad($semNum, 2, '0', STR_PAD_LEFT) . '_' . $filiere->nom;
                    } else {
                        return '1_' . $filiere->nom;
                    }
                });
            }
            $this->is_mode_libre = false;
        }
        $this->reset('filiere_id', 'module_id', 'is_new_module', 'new_module_nom');
    }

    public function updatedFiliereId() {
        $this->modules = Module::where('filiere_id', $this->filiere_id)->orderBy('nom', 'asc')->get();
        $this->reset('module_id', 'is_new_module', 'new_module_nom');
    }

    public function openModalCreate($date_preselectionnee = null) {
        $this->resetForm();
        $this->isUpdateMode = false;
        $this->is_mode_libre = false;
        $this->is_mode_reservation = false;
        $this->minDate = Carbon::today()->format('Y-m-d');

        if ($date_preselectionnee) {
            try {
                $d = Carbon::parse($date_preselectionnee);
                $dateToUse = ($d->isPast() && !$d->isToday()) ? Carbon::today()->format('Y-m-d') : $d->format('Y-m-d');
                $this->date_debut = $dateToUse;
                $this->date_fin = $dateToUse;
            } catch (\Exception $e) {
                $this->date_debut = Carbon::today()->format('Y-m-d');
            }
        } else {
            $this->date_debut = Carbon::today()->format('Y-m-d');
        }

        $this->numero_ordre = $this->calculatePositionByDate($this->date_debut);
        $this->chauffeur = 'LAKHAL HAMZA';
        $this->dispatch('showSortieModal');
    }

    public function openModalReservation() {
        $this->resetForm();
        $this->isUpdateMode = false;
        $this->is_mode_reservation = true;
        $this->is_mode_libre = false;
        $this->minDate = Carbon::today()->format('Y-m-d');

        $this->date_debut = Carbon::today()->format('Y-m-d');
        $this->date_fin = Carbon::today()->format('Y-m-d');
        $this->numero_ordre = $this->calculatePositionByDate($this->date_debut);
        $this->chauffeur = 'LAKHAL HAMZA';
        $this->dispatch('showReservationModal');
    }

    public function openModalOrdreLibre() {
        $this->resetForm();
        $this->isUpdateMode = false;
        $this->is_mode_libre = true;
        $this->minDate = Carbon::today()->format('Y-m-d');
        $this->date_debut = Carbon::today()->format('Y-m-d');
        $this->date_fin = Carbon::today()->format('Y-m-d');
        $this->numero_ordre = $this->calculatePositionByDate($this->date_debut);
        // MODIFICATION ICI : Chauffeur vide par défaut pour permettre le choix
        // $this->chauffeur = 'LAKHAL HAMZA';
        $this->dispatch('showOrdreLibreModal');
    }

    public function openModalEdit(Sortie $sortie) {
        $this->resetForm();
        $this->isUpdateMode = true;
        $this->sortie_id = $sortie->id;
        $this->numero_ordre = $sortie->numero_ordre;
        $this->date_debut = $sortie->date_debut->format('Y-m-d');
        $this->date_fin = $sortie->date_fin->format('Y-m-d');
        $this->minDate = null;
        $this->chauffeur = $sortie->chauffeur;
        $this->transport = $sortie->transport ? $sortie->transport->value : null;

        if ($sortie->statut === 'Réservé') {
            $this->is_mode_reservation = true;
            $this->departement_id = $sortie->departement_id;
            $this->objet = $sortie->objet;
            $this->dispatch('showReservationModal');
        }
        elseif ($sortie->objet && !$sortie->module_id && !$sortie->destination_id) {
            $this->is_mode_libre = true;
            $this->objet = $sortie->objet;
            $this->personnel = $sortie->personnel;
            $this->dispatch('showOrdreLibreModal');
        }
        else {
            $this->is_mode_libre = false;
            $this->destination_id = $sortie->destination_id;

            if ($sortie->objet && $sortie->destination_id && !$sortie->module_id) {
                 $this->is_special_dept = true;
                 $this->objet = $sortie->objet;
                 $this->personnel = $sortie->personnel;
            }

            if ($sortie->departement_id) {
                $this->departement_id = $sortie->departement_id;
                $this->updatedDepartementId();
            }

            $this->encadrants_ids = $sortie->encadrants->pluck('id')->toArray();

            if ($sortie->module) {
                $this->module_id = $sortie->module_id;
                if ($sortie->module->filiere) {
                    $this->filiere_id = $sortie->module->filiere_id;
                    $this->modules = Module::where('filiere_id', $this->filiere_id)->get();
                    if ($sortie->module->filiere->departement) {
                        $this->departement_id = $sortie->module->filiere->departement_id;
                        $this->updatedDepartementId();
                    }
                }
            }
            $this->dispatch('showSortieModal');
        }
    }

    public function openModalConvert(Sortie $sortie) {
        $this->resetForm();
        $this->isUpdateMode = true;
        $this->sortie_id = $sortie->id;

        $this->numero_ordre = $sortie->numero_ordre;
        $this->date_debut = $sortie->date_debut->format('Y-m-d');
        $this->date_fin = $sortie->date_fin->format('Y-m-d');
        $this->chauffeur = $sortie->chauffeur;
        $this->departement_id = $sortie->departement_id;
        $this->transport = $sortie->transport ? $sortie->transport->value : null;

        $this->is_mode_reservation = false;
        $this->is_mode_libre = false;

        if ($this->departement_id) {
            $this->updatedDepartementId();
        }

        $this->dispatch('showSortieModal');
    }

    public function closeModal() {
        $this->dispatch('hideSortieModal');
        $this->dispatch('hideOrdreLibreModal');
        $this->dispatch('hideReservationModal');
        $this->resetForm();
    }

    private function resetForm() {
        $this->resetErrorBag();
        $this->reset([
            'numero_ordre', 'date_debut', 'date_fin', 'chauffeur', 'transport',
            'departement_id', 'filiere_id', 'module_id', 'destination_id', 'encadrants_ids', 'encadrant_to_add',
            'objet', 'personnel', 'sortie_id', 'isUpdateMode', 'is_mode_libre', 'is_mode_reservation', 'is_special_dept',
            'is_new_destination', 'new_destination_nom',
            'is_new_module', 'new_module_nom',
            'is_new_encadrant', 'new_encadrant_nom'
        ]);
        $this->filieres = collect();
        $this->modules = collect();
    }

    public function saveSortie() {
        $this->validate();

        DB::transaction(function () {
            if ($this->departement_id && !$this->is_mode_reservation) {
                $this->is_mode_libre = false;
            }

            $year = Carbon::parse($this->date_debut)->year;
            $finalOrdre = $this->numero_ordre;

            if (!$this->isUpdateMode) {
                $exists = Sortie::whereYear('date_debut', $year)->where('numero_ordre', $finalOrdre)->exists();
                if ($exists) {
                    $this->safeShiftOrders($year, $finalOrdre);
                }
            } else {
                $exists = Sortie::whereYear('date_debut', $year)
                    ->where('numero_ordre', $finalOrdre)
                    ->where('id', '!=', $this->sortie_id)
                    ->exists();
                if($exists) $this->safeShiftOrders($year, $finalOrdre);
            }

            $data = [
                'numero_ordre' => $finalOrdre,
                'date_debut' => $this->date_debut,
                'date_fin' => $this->date_fin,
                'transport' => $this->transport ?: null,
                'chauffeur' => $this->chauffeur,
                'statut' => 'Confirmé',
            ];

            if ($this->is_mode_reservation) {
                $data['statut'] = 'Réservé';
                $data['departement_id'] = $this->departement_id;
                $data['objet'] = $this->objet;
                $data['module_id'] = null;
                $data['destination_id'] = null;
                $data['personnel'] = null;
            }
            elseif ($this->is_mode_libre) {
                $data['objet'] = $this->objet;
                $data['personnel'] = $this->personnel;
                $data['module_id'] = null;
                $data['destination_id'] = null;
                $data['departement_id'] = null;
            }
            else {
                if ($this->is_new_destination) {
                    $dest = Destination::firstOrCreate(['nom' => trim($this->new_destination_nom)]);
                    $data['destination_id'] = $dest->id;
                } else {
                    $data['destination_id'] = $this->destination_id;
                }

                if ($this->is_special_dept) {
                    $dept = Departement::find($this->departement_id);
                    $nomDept = $dept ? $dept->nom : 'Département';
                    $objetFinal = !empty($this->objet) ? $this->objet : $nomDept;
                    $personnelFinal = !empty($this->personnel) ? $this->personnel : "Prs " . $nomDept;

                    $data['objet'] = $objetFinal;
                    $data['personnel'] = $personnelFinal;
                    $data['departement_id'] = $this->departement_id;
                    $data['module_id'] = null;
                } else {
                    if ($this->is_new_module) {
                        $mod = Module::firstOrCreate(
                            ['nom' => trim($this->new_module_nom)],
                            ['filiere_id' => $this->filiere_id]
                        );
                        $data['module_id'] = $mod->id;
                    } else {
                        $data['module_id'] = $this->module_id;
                    }
                    $data['departement_id'] = $this->departement_id;
                    $data['objet'] = null;
                    $data['personnel'] = null;
                }
            }

            $sortie = Sortie::updateOrCreate(['id' => $this->sortie_id], $data);

            if (!$this->is_mode_libre && !$this->is_mode_reservation && !$this->is_special_dept) {
                $sortie->encadrants()->sync($this->encadrants_ids);
            } else {
                $sortie->encadrants()->detach();
            }
        });

        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Enregistré avec succès.']);
        $this->closeModal();
        $this->dispatch('refresh');
    }

    public function confirmDelete($id) {
        $this->dispatch('showDeleteConfirmation', id: $id);
    }

    public function deleteSortie($id) {
        $sortie = Sortie::find($id);
        if($sortie) {
            $sortie->encadrants()->detach();
            $sortie->delete();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Suppression effectuée.']);
            $this->dispatch('refresh');
        }
    }

    public function validateAndPrint($id, $format)
    {
        $sortie = Sortie::find($id);
        if ($sortie) {
            $sortie->update(['date_validation' => Carbon::now()]);
            $link = ($sortie->module_id || $sortie->destination_id) ? route('admin.print_sortie', ['id' => $id, 'format' => $format]) : route('admin.print_ordre_libre', ['id' => $id, 'format' => $format]);
            $this->dispatch('startDownload', url: $link);
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Validation effectuée. Téléchargement en cours...']);
        }
    }

    public function cancelValidation($id)
    {
        $sortie = Sortie::find($id);
        if ($sortie) {
            $sortie->update(['date_validation' => null]);
            $this->dispatch('showToastr', ['type' => 'info', 'message' => 'Validation annulée. Date réinitialisée.']);
            $this->dispatch('refresh');
        }
    }

    public function render() {
        $departements_list = Departement::orderBy('nom')->get();
        $destinations_list = Destination::orderBy('nom')->get();
        $transports_list = TypeTransport::cases();

        $personnels_list = Personnel::with('departement')
            ->when($this->departement_id, function($q) {
                $q->where('departement_id', $this->departement_id);
            })
            ->orderBy('nom')->get();

        $selected_encadrants_objects = Personnel::whereIn('id', $this->encadrants_ids)->get();

        $startOfMonth = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth()->endOfDay();
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $sorties_standard = Sortie::with(['destination', 'module.filiere.departement', 'encadrants', 'departement'])
            ->where(function($query) {
                $query->whereNotNull('destination_id')
                      ->orWhere('statut', 'Réservé');
            })
            ->where('statut', '!=', 'Annulé')
            ->where(function($q) use ($startOfMonth, $endOfMonth) {
                 $q->whereBetween('date_debut', [$startOfMonth, $endOfMonth])
                   ->orWhereBetween('date_fin', [$startOfMonth, $endOfMonth]);
            })
            ->orderBy('numero_ordre', 'asc')
            ->get();

        $ordres_libres = Sortie::whereNull('module_id')
            ->whereNull('destination_id')
            ->where('statut', '!=', 'Réservé')
            ->where('statut', '!=', 'Annulé')
            ->where(function($q) use ($startOfMonth, $endOfMonth) {
                 $q->whereBetween('date_debut', [$startOfMonth, $endOfMonth])
                   ->orWhereBetween('date_fin', [$startOfMonth, $endOfMonth]);
            })
            ->orderBy('numero_ordre', 'desc')
            ->get();

        $jours_du_mois = [];
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $found = $sorties_standard->first(fn($s) =>
                $d >= $s->date_debut->format('Y-m-d') && $d <= $s->date_fin->format('Y-m-d')
            );
            $jours_du_mois[] = ['date_obj' => $date, 'is_weekend' => $date->isWeekend(), 'sortie' => $found];
        }

        // --- CALCUL DE L'ANNÉE DE DÉBUT DE SAISON ---
        // Si le mois est >= 9 (Sept), la saison commence l'année courante (ex: Sept 2025 -> 2025-2026)
        // Si le mois est < 9 (Jan-Août), la saison a commencé l'année d'avant (ex: Jan 2026 -> 2025-2026)
        $startSeasonYear = ($this->selectedMonth >= 9)
                            ? $this->selectedYear
                            : ($this->selectedYear - 1);

        return view('livewire.admin.dashboard', [
            'departements' => $departements_list,
            'destinations' => $destinations_list,
            'transports' => $transports_list,
            'personnels_list' => $personnels_list,
            'selected_encadrants_objects' => $selected_encadrants_objects,
            'jours_du_mois' => $jours_du_mois,
            'ordres_libres' => $ordres_libres,
            'today' => Carbon::today()->format('Y-m-d'),
            // Variable transmise à la vue
            'startSeasonYear' => $startSeasonYear,
        ]);
    }
}
