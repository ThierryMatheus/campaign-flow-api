<?php

use App\Models\FieldActivity;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a field activity', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->postJson('/api/field-activities', [
        'workspace_id' => $workspace->id,
        'type' => 'visit',
        'result' => 'positive',
        'notes' => 'Morador demonstrou apoio',
        'performed_at' => now()->toDateTimeString(),
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.type', 'visit')
        ->assertJsonPath('data.user_id', $user->id);

    $this->assertDatabaseHas('field_activities', [
        'workspace_id' => $workspace->id,
        'type' => 'visit',
        'user_id' => $user->id,
    ]);
});

it('can list field activities from own workspace', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    FieldActivity::factory()->count(3)->create([
        'workspace_id' => $workspace->id,
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/field-activities?workspace_id=' . $workspace->id);

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('cannot access field activity from another workspace', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherWorkspace = Workspace::factory()->create(['owner_id' => $otherUser->id]);

    $activity = FieldActivity::factory()->create([
        'workspace_id' => $otherWorkspace->id,
        'user_id' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->getJson("/api/field-activities/{$activity->id}");

    $response->assertForbidden();
});

it('can soft delete a field activity', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $activity = FieldActivity::factory()->create([
        'workspace_id' => $workspace->id,
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/field-activities/{$activity->id}");

    $response->assertOk();
    $this->assertSoftDeleted('field_activities', ['id' => $activity->id]);
});
