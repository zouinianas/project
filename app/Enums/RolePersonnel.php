<?php

namespace App\Enums;

enum RolePersonnel: string
{
    case Enseignant = 'Enseignant';
    case Chauffeur = 'Chauffeur';
    case Technicien = 'Technicien';
}
