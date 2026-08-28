<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\WorkspaceType;
use App\Enums\WorkspaceStatus;

class Workspace extends Model
{
    use HasFactory, SoftDeletes;
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
}
