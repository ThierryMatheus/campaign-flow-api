<?php

namespace App\Enums;

enum TeamType: string
{
    case Regional = 'regional';
    case Sector = 'sector';
    case Street = 'street';
    case Support = 'support';
}
