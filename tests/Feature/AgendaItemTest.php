<?php

use App\Models\AgendaItem;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create an agenda item', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->postJson('/api/agenda-items', [
        'workspace_id' => $workspace->id,
        'title' => 'Reunião com lideranças',
        'type' => 'meeting',
        'starts_at' => now()->addDay()->toDateTimeString(),
        'location' => 'Gabinete Central',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.title', 'Reunião com lideranças')
        ->assertJsonPath('data.created_by', $user->id);

    $this->assertDatabaseHas('agenda_items', [
        'title' => 'Reunião com lideranças',
        'workspace_id' => $workspace->id,
    ]);
});

it('can list agenda items from own workspace', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    AgendaItem::factory()->count(3)->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/agenda-items?workspace_id=' . $workspace->id);

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can filter agenda items by type', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    AgendaItem::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'type' => 'meeting',
    ]);

    AgendaItem::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'type' => 'event',
    ]);

    $response = $this->actingAs($user)->getJson(
        '/api/agenda-items?workspace_id=' . $workspace->id . '&type=meeting'
    );

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('cannot access agenda item from another workspace', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherWorkspace = Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $item = AgendaItem::factory()->create([
        'workspace_id' => $otherWorkspace->id,
        'created_by' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->getJson("/api/agenda-items/{$item->id}");

    $response->assertForbidden();
});

it('can soft delete an agenda item', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $item = AgendaItem::factory()->create([
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/agenda-items/{$item->id}");

    $response->assertOk();
    $this->assertSoftDeleted('agenda_items', ['id' => $item->id]);
});
