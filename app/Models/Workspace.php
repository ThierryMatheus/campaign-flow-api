<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workspace extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name','slug','type','election_year','city','state','status','owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
