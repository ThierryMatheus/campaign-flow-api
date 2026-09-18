<?php

namespace App\Enums;

enum TransactionType: string
{
    case Donation = 'donation';
    case Expense = 'expense';
}
