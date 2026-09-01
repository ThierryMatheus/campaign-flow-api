<?php

namespace App\Enums;

enum FieldActivityResult: string
{
    case Positive = 'positive';
    case Neutral = 'neutral';
    case Negative = 'negative';
    case NotHome = 'not_home';
    case Refused = 'refused';
}
