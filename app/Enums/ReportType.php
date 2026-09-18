<?php

namespace App\Enums;

enum ReportType: string
{
    case Dashboard = 'dashboard';
    case Voters = 'voters';
    case Transactions = 'transactions';
}
