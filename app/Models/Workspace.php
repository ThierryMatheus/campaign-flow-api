<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\WorkspaceType;
use App\Enums\WorkspaceStatus;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Workspace extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['name','slug','type','election_year','city','state','status','owner_id'];

    protected $casts = ['type' => WorkspaceType::class,'status' => WorkspaceStatus::class,'election_year' => 'integer'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'workspace_user')->withPivot(['role', 'is_primary', 'joined_at', 'left_at'])->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
