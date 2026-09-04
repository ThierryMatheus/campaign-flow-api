<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @method static \Database\Factories\AgendaItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgendaItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgendaItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgendaItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgendaItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgendaItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgendaItem withoutTrashed()
 */
	class AgendaItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property \App\Enums\DemandStatus $status
 * @property \App\Enums\DemandPriority $priority
 * @property-read \App\Models\User|null $assignee
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Voter|null $voter
 * @property-read \App\Models\Workspace|null $workspace
 * @method static \Database\Factories\DemandFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Demand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Demand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Demand onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Demand query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Demand withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Demand withoutTrashed()
 */
	class Demand extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $workspace_id
 * @property int|null $voter_id
 * @property int|null $team_id
 * @property int $user_id
 * @property \App\Enums\FieldActivityType $type
 * @property \App\Enums\FieldActivityResult|null $result
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $performed_at
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Team|null $team
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Voter|null $voter
 * @property-read \App\Models\Workspace|null $workspace
 * @method static \Database\Factories\FieldActivityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity wherePerformedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereVoterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity whereWorkspaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FieldActivity withoutTrashed()
 */
	class FieldActivity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $workspace_id
 * @property int|null $parent_id
 * @property string $name
 * @property \App\Enums\TeamType|null $type
 * @property int|null $leader_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Team> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\User|null $leader
 * @property-read Team|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voter> $voters
 * @property-read int|null $voters_count
 * @property-read \App\Models\Workspace|null $workspace
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereLeaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereWorkspaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team withoutTrashed()
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Workspace> $workspaces
 * @property-read int|null $workspaces_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $workspace_id
 * @property string $name
 * @property string|null $cpf
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $gender
 * @property string|null $street
 * @property string|null $number
 * @property string|null $neighborhood
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zipcode
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property \App\Enums\VoterStatus $status
 * @property \App\Enums\VoterOrigin|null $origin
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \App\Models\Workspace|null $worskpace
 * @method static \Database\Factories\VoterFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereCpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereNeighborhood($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereWorkspaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereZipcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter withoutTrashed()
 */
	class Voter extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \App\Enums\WorkspaceType $type
 * @property int|null $election_year
 * @property string|null $city
 * @property string|null $state
 * @property \App\Enums\WorkspaceStatus $status
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\WorkspaceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereElectionYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workspace withoutTrashed()
 */
	class Workspace extends \Eloquent {}
}

