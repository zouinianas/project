<?php

namespace App\Enums;

enum NiveauCycle: string
{
    // Modification pour correspondre à votre demande
    case DEUG_A1 = 'DEUG 1ère Année';
    case DEUG_A2 = 'DEUG 2ème Année';
    case Licence = 'Licence';
    case Master = 'Master';

    /**
     * Retourne les semestres associés à ce niveau
     */
    public function semestres(): array
    {
        return match($this) {
            self::DEUG_A1 => [Semestre::S1, Semestre::S2],
            self::DEUG_A2 => [Semestre::S3, Semestre::S4],
            self::Licence => [Semestre::S5, Semestre::S6],
            self::Master => [Semestre::S1, Semestre::S2, Semestre::S3, Semestre::S4],
        };
    }
}
