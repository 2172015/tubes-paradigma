<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'promo_id',
        'total_amount',
        'status',
        'payment_proof',
        'invoice_code'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function promo(){
        return $this->belongsTo(Promo::class);
    }

    public function transactionDetail() {
        return $this->hasMany(TransactionDetail::class);
    }
}
