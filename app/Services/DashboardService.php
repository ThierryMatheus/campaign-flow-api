<?php

namespace App\Services;

use App\Models\AgendaItem;
use App\Models\Demand;
use App\Models\FieldActivity;
use App\Models\Transaction;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;

 class DashboardService{
    public function summary(int $workspace_id): array
    {
        $votersByStatus = Voter::query()
        ->where('workspace_id', $workspace_id)
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

         $totalDonations = Transaction::query()
        ->where('workspace_id', $workspace_id)
        ->where('type', 'donation')
        ->sum('amount');

         $totalExpenses = Transaction::query()
        ->where('workspace_id', $workspace_id)
        ->where('type', 'expense')
        ->sum('amount');

         return [
            'voters' => [
                'total' => Voter::where('workspace_id', $workspace_id)->count(),
                'by_status' => [
                    'supporter' => $votersByStatus['supporter'] ?? 0,
                    'undecided' => $votersByStatus['undecided'] ?? 0,
                    'opponent' => $votersByStatus['opponent'] ?? 0,
                    'unknown' => $votersByStatus['unknown'] ?? 0,
                ],
            ],
            'demands' => [
                'open' => Demand::where('workspace_id', $workspace_id)->where('status', 'open')->count(),
                'in_progress' => Demand::where('workspace_id', $workspace_id)->where('status', 'in_progress')->count(),
                'resolved' => Demand::where('workspace_id', $workspace_id)->where('status', 'resolved')->count(),
            ],
            'finance' => [
                'total_donations' => (float) $totalDonations,
                'total_expenses' => (float) $totalExpenses,
                'balance' => (float) ($totalDonations - $totalExpenses),
            ],
            'field_activities' => [
                'total' => FieldActivity::where('workspace_id', $workspace_id)->count(),
                'last_7_days' => FieldActivity::where('workspace_id', $workspace_id)->where('performed_at', '>=', now()->subDays(7))->count(),
            ],
            'agenda' => [
                'upcoming' => AgendaItem::where('workspace_id', $workspace_id)
                ->where('starts_at', '=>', now())
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count()
            ]
        ];
    }
}
