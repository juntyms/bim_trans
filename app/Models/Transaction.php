<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount','user_id','due_on','vat','is_vat','status_id'
    ];

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function status()
    {
        return $this->hasOne(Status::class,'id','status_id');
    }
}
