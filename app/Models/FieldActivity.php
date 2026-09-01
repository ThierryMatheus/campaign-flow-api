<?php

namespace App\Models;

use App\Enums\FieldActivityResult;
use App\Enums\FieldActivityType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workspace_id', 'voter_id', 'team_id', 'user_id',
        'type', 'result', 'notes', 'performed_at',
        'latitude', 'longitude',
    ];

    protected $casts = [
        'type' => FieldActivityType::class,
        'result' => FieldActivityResult::class,
        'performed_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }

    public function voter(){
        return $this->belongsTo(Voter::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }
}
