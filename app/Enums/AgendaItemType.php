<?php

namespace App\Enums;

enum AgendaItemType: string
{
    case Meeting = 'meeting';
    case Event = 'event';
    case Visit = 'visit';
    case Hearing = 'hearing';
    case Other = 'other';
}
