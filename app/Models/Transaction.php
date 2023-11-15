<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount','user_id','due_on','vat','is_vat'
    ];

    public function payer()
    {
        return $this->belongsTo(User::class);
    }
}
