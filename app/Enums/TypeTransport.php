<?php

namespace App\Enums;

enum TypeTransport: string
{
    case MINI_BUS = 'MINI BUS M161513';
    case BUS = 'BUS M147130';
    case VOITURE_PERSONNELLE = 'Voiture Personnelle';

    case TRANSPORT_PERSONNELLE = 'Transport Personnelle';
}
