<?php

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

it('logs activity when a voter is created', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $this->actingAs($user)->postJson('/api/voters', [
        'workspace_id' => $workspace->id,
        'name' => 'Eleitor Teste',
        'status' => 'supporter',
    ])->assertStatus(201);

    expect(Activity::where('description', 'created')->count())->toBeGreaterThan(0);
});

it('can list activity logs', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->getJson('/api/activity-logs');

    $response->assertOk();
});
