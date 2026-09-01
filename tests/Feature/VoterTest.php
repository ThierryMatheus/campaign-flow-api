<?php

use App\Models\User;
use App\Models\Voter;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a voter', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->postJson('/api/voters', [
        'workspace_id' => $workspace->id,
        'name' => 'João Silva',
        'cpf' => '123.456.789-00',
        'status' => 'supporter',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'João Silva');

    $this->assertDatabaseHas('voters', [
        'name' => 'João Silva',
        'workspace_id' => $workspace->id,
    ]);
});

it('can list voters from own workspace', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Voter::factory()->count(3)->create(['workspace_id' => $workspace->id]);

    $response = $this->actingAs($user)->getJson('/api/voters?workspace_id=' . $workspace->id);

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('cannot access voter from another workspace', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherWorkspace = Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $voter = Voter::factory()->create(['workspace_id' => $otherWorkspace->id]);

    $response = $this->actingAs($user)->getJson("/api/voters/{$voter->id}");

    $response->assertForbidden();
});

it('can update a voter', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $voter = Voter::factory()->create(['workspace_id' => $workspace->id]);

    $response = $this->actingAs($user)->putJson("/api/voters/{$voter->id}", [
        'name' => 'Nome Atualizado',
        'status' => 'undecided',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Nome Atualizado');
});

it('can soft delete a voter', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $voter = Voter::factory()->create(['workspace_id' => $workspace->id]);

    $response = $this->actingAs($user)->deleteJson("/api/voters/{$voter->id}");

    $response->assertOk();
    $this->assertSoftDeleted('voters', ['id' => $voter->id]);
});

it('can filter voters by status', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Voter::factory()->create(['workspace_id' => $workspace->id, 'status' => 'supporter']);
    Voter::factory()->create(['workspace_id' => $workspace->id, 'status' => 'undecided']);

    $response = $this->actingAs($user)->getJson('/api/voters?workspace_id=' . $workspace->id . '&status=supporter');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});
