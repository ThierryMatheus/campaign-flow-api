<?php

use App\Models\Demand;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Voter;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can get dashboard summary', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Voter::factory()->count(3)->create([
        'workspace_id' => $workspace->id,
        'status' => 'supporter',
    ]);

    Voter::factory()->create([
        'workspace_id' => $workspace->id,
        'status' => 'undecided',
    ]);

    Demand::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'status' => 'open',
    ]);

    Transaction::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'type' => 'donation',
        'amount' => 1000,
    ]);

    Transaction::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'type' => 'expense',
        'amount' => 300,
    ]);

    $response = $this->actingAs($user)->getJson(
        '/api/dashboard/summary?workspace_id=' . $workspace->id
    );

    $response->assertOk()
        ->assertJsonPath('voters.total', 4)
        ->assertJsonPath('voters.by_status.supporter', 3)
        ->assertJsonPath('demands.open', 1)
        ->assertJsonPath('finance.total_donations', 1000)
        ->assertJsonPath('finance.total_expenses', 300)
        ->assertJsonPath('finance.balance', 700);
});

it('cannot access dashboard from another workspace', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherWorkspace = Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $response = $this->actingAs($user)->getJson(
        '/api/dashboard/summary?workspace_id=' . $otherWorkspace->id
    );

    $response->assertForbidden();
});
