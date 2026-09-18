<?php

use App\Models\Demand;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a demand', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->postJson('/api/demands', [
        'workspace_id' => $workspace->id,
        'title' => 'Pedido de asfalto na Rua X',
        'priority' => 'high',
        'category' => 'infrastructure',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.title', 'Pedido de asfalto na Rua X')
        ->assertJsonPath('data.created_by', $user->id);

    $this->assertDatabaseHas('demands', [
        'title' => 'Pedido de asfalto na Rua X',
        'workspace_id' => $workspace->id,
    ]);
});

it('can list demands from own workspace', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Demand::factory()->count(3)->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/demands?workspace_id=' . $workspace->id);

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can filter demands by status', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Demand::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'status' => 'open',
    ]);

    Demand::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'status' => 'resolved',
    ]);

    $response = $this->actingAs($user)->getJson(
        '/api/demands?workspace_id=' . $workspace->id . '&status=open'
    );

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('cannot access demand from another workspace', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherWorkspace = Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $demand = Demand::factory()->create([
        'workspace_id' => $otherWorkspace->id,
        'created_by' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->getJson("/api/demands/{$demand->id}");

    $response->assertForbidden();
});

it('can soft delete a demand', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $demand = Demand::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/demands/{$demand->id}");

    $response->assertOk();
    $this->assertSoftDeleted('demands', ['id' => $demand->id]);
});
