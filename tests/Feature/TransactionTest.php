<?php

use App\Models\Transaction;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a transaction', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->postJson('/api/transactions', [
        'workspace_id' => $workspace->id,
        'type' => 'donation',
        'title' => 'Doação de João',
        'amount' => 500.00,
        'occurred_at' => now()->toDateString(),
        'category' => 'transfer',
        'donor_or_vendor' => 'João Silva',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.type', 'donation')
        ->assertJsonPath('data.created_by', $user->id);

    $this->assertDatabaseHas('transactions', [
        'title' => 'Doação de João',
        'workspace_id' => $workspace->id,
        'type' => 'donation',
    ]);
});

it('can list transactions from own workspace', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Transaction::factory()->count(3)->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/transactions?workspace_id=' . $workspace->id);

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can filter transactions by type', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Transaction::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'type' => 'donation',
    ]);

    Transaction::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'type' => 'expense',
    ]);

    $response = $this->actingAs($user)->getJson(
        '/api/transactions?workspace_id=' . $workspace->id . '&type=donation'
    );

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('cannot access transaction from another workspace', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherWorkspace = Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $transaction = Transaction::factory()->create([
        'workspace_id' => $otherWorkspace->id,
        'created_by' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->getJson("/api/transactions/{$transaction->id}");

    $response->assertForbidden();
});

it('can soft delete a transaction', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $transaction = Transaction::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/transactions/{$transaction->id}");

    $response->assertOk();
    $this->assertSoftDeleted('transactions', ['id' => $transaction->id]);
});
