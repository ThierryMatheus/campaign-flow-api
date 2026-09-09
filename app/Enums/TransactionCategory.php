<?php

namespace App\Enums;

enum TransactionCategory: string
{
    case Cash = 'cash';
    case Transfer = 'transfer';
    case Material = 'material';
    case Fuel = 'fuel';
    case Advertising = 'advertising';
    case Event = 'event';
    case Other = 'other';
}
