<?php

namespace App\Models;

use App\Enums\VoterOrigin;
use App\Enums\VoterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'workspace_id', 'name', 'cpf', 'phone', 'email', 'birth_date', 'gender',
      'street', 'number', 'neighborhood', 'city', 'state', 'zipcode',
      'latitude', 'longitude', 'status', 'origin', 'notes'
    ];

    protected $casts = [
        'status' => VoterStatus::class,
        'origin' => VoterOrigin::class,
        'birth_date' => 'date',
        'latitude' => 'decimal:8',
        'longitode' => 'decimal:8'
    ];

    public function worskpace(){
        return $this->belongsTo(Workspace::class);
    }
}
