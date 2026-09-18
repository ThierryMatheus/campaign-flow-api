<?php

namespace App\Enums;

enum VoterOrigin: string
{
    case DoorToDoor = 'door_to_door';
    case Event = 'event';
    case Social = 'social';
    case Import = 'import';
    case Referral = 'referral';
}
