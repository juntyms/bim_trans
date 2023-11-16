<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id','amount','paid_on','details'];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class,'transaction_id','id');
    }
}
