<?php

namespace App\Enums;

enum VoterStatus: string
{
    case Supporter = 'supporter';
    case Undecided = 'undecided';
    case Opponent = 'opponent';
    case Unknown = 'unknown';
}
