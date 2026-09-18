<?php

use App\Models\Team;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a team', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->postJson('/api/teams', [
        'workspace_id' => $workspace->id,
        'name' => 'Equipe Rua A',
        'type' => 'street',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Equipe Rua A');

    $this->assertDatabaseHas('teams', [
        'name' => 'Equipe Rua A',
        'workspace_id' => $workspace->id,
    ]);
});

it('can create a child team', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $parent = Team::factory()->create(['workspace_id' => $workspace->id]);

    $response = $this->actingAs($user)->postJson('/api/teams', [
        'workspace_id' => $workspace->id,
        'parent_id' => $parent->id,
        'name' => 'Subequipe',
        'type' => 'street',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.parent_id', $parent->id);
});

it('can list teams from own workspace', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Team::factory()->count(3)->create(['workspace_id' => $workspace->id]);

    $response = $this->actingAs($user)->getJson('/api/teams?workspace_id=' . $workspace->id);

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('cannot access team from another workspace', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherWorkspace = Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $team = Team::factory()->create(['workspace_id' => $otherWorkspace->id]);

    $response = $this->actingAs($user)->getJson("/api/teams/{$team->id}");

    $response->assertForbidden();
});

it('can soft delete a team', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $team = Team::factory()->create(['workspace_id' => $workspace->id]);

    $response = $this->actingAs($user)->deleteJson("/api/teams/{$team->id}");

    $response->assertOk();
    $this->assertSoftDeleted('teams', ['id' => $team->id]);
});
