<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TeamType;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workspace_id','parent_id','name','type','leader_id',
    ];

    protected $casts = [
        'type' => TeamType::class,
    ];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }

    public function parent(){
        return $this->belongsTo(Team::class, 'parent_id');
    }

    public function children(){
        return $this->hasMany(Team::class, 'parent_id');
    }

    public function leader(){
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function voters(){
        return $this->belongsToMany(Voter::class, 'voter_team')->withTimestamps();
    }
}
