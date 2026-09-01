<?php

namespace App\Enums;

enum FieldActivityType: string
{
    case Visit = 'visit';
    case Call = 'call';
    case Event = 'event';
    case Distribution = 'distribution';
    case Other = 'other';
}
