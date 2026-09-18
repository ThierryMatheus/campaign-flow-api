<?php

use App\Models\Report;
use App\Models\User;
use App\Models\Voter;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('can request a dashboard report and download when ready', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    $response = $this->actingAs($user)->postJson('/api/reports', [
        'workspace_id' => $workspace->id,
        'type' => 'dashboard',
        'format' => 'csv',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('type', 'dashboard');

    $report = Report::first();
    expect($report->status->value)->toBe('ready');
    expect($report->file_path)->not->toBeNull();

    $download = $this->actingAs($user)->get("/api/reports/{$report->id}/download");
    $download->assertOk();
});

it('can request a voters report', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Voter::factory()->count(3)->create(['workspace_id' => $workspace->id]);

    $response = $this->actingAs($user)->postJson('/api/reports', [
        'workspace_id' => $workspace->id,
        'type' => 'voters',
    ]);

    $response->assertStatus(201);

    $report = Report::first();
    expect($report->status->value)->toBe('ready');

    $this->actingAs($user)
        ->get("/api/reports/{$report->id}/download")
        ->assertOk();
});

it('cannot access report from another user workspace', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $otherWorkspace = Workspace::factory()->create(['owner_id' => $other->id]);

    $report = Report::create([
        'workspace_id' => $otherWorkspace->id,
        'user_id' => $other->id,
        'type' => 'dashboard',
        'format' => 'csv',
        'status' => 'ready',
        'file_path' => 'reports/x.csv',
    ]);

    $this->actingAs($user)
        ->getJson("/api/reports/{$report->id}")
        ->assertForbidden();
});

it('lists only own reports for workspace', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id, ['role' => 'admin']);

    Report::create([
        'workspace_id' => $workspace->id,
        'user_id' => $user->id,
        'type' => 'dashboard',
        'format' => 'csv',
        'status' => 'ready',
    ]);

    $this->actingAs($user)
        ->getJson('/api/reports?workspace_id=' . $workspace->id)
        ->assertOk()
        ->assertJsonPath('data.0.type', 'dashboard');
});
