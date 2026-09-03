<?php

namespace App\Models;

use App\Enums\DemandPriority;
use App\Enums\DemandStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workspace_id', 'voter_id', 'created_by', 'assigned_to',
        'title', 'description', 'status', 'priority', 'category', 'resolved_at'
    ];

    protected $casts = [
        'status' => DemandStatus::class,
        'priority' => DemandPriority::class,
        'resolved_at' => 'datetime'
    ];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }

    public function voter(){
        return $this->belongsTo(Voter::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(){
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
