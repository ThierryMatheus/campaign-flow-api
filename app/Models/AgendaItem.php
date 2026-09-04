<?php

namespace App\Models;

use App\Enums\AgendaItemStatus;
use App\Enums\AgendaItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'workspace_id', 'team_id', 'voter_id', 'created_by',
      'title', 'description', 'type', 'status', 'starts_at',
      'ends_at', 'location', 'latitude', 'longitude'
    ];

    protected $casts = [
      'type' => AgendaItemType::class,
      'status' => AgendaItemStatus::class,
      'starts_at' => 'datetime',
      'ends_at' => 'datetime',
      'latitude' => 'decimal:8'  ,
      'longitude' => 'decimal:8'
    ];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }
    public function voter(){
        return $this->belongsTo(Voter::class);
    }
    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
