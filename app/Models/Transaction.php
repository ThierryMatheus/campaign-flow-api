<?php

namespace App\Models;

use App\Enums\TransactionCategory;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workspace_id','created_by','type','category','title','description',
        'amount','occurred_at','payment_method','donor_or_vendor'
    ];

    protected $casts = [
        'type' => TransactionType::class,
        'category' => TransactionCategory::class,
        'amount' => 'decimal:2',
        'occurred_at' => 'date'
    ];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
