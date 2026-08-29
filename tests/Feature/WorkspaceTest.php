<?php

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a workspace', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/workspaces', [
        'name' => 'Campaign 2026',
        'type' => 'campaign',
        'election_year' => 2026,
        'city' => 'Bologna',
        'state' => 'BO',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Campaign 2026')
        ->assertJsonPath('data.type', 'campaign');

    $this->assertDatabaseHas('workspaces', [
        'name' => 'Campaign 2026',
        'owner_id' => $user->id,
    ]);

    $this->assertDatabaseHas('workspace_user', [
        'user_id' => $user->id,
        'role' => 'admin',
        'is_primary' => true,
    ]);
});

it('can list only own workspaces', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $ownWorkspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $ownWorkspace->users()->attach($user->id, ['role' => 'admin', 'is_primary' => true]);

    Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $response = $this->actingAs($user)->getJson('/api/workspaces');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('can show a workspace the user belongs to', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->getJson("/api/workspaces/{$workspace->id}");

    $response->assertOk()
        ->assertJsonPath('data.id', $workspace->id);
});

it('cannot show a workspace the user does not belong to', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $response = $this->actingAs($user)->getJson("/api/workspaces/{$workspace->id}");

    $response->assertForbidden();
});

it('can update a workspace as owner', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->putJson("/api/workspaces/{$workspace->id}", [
        'name' => 'Updated Name',
        'status' => 'active',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Updated Name');
});

it('can delete a workspace as owner', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);

    $response = $this->actingAs($user)->deleteJson("/api/workspaces/{$workspace->id}");

    $response->assertOk();
    $this->assertSoftDeleted('workspaces', ['id' => $workspace->id]);
});
