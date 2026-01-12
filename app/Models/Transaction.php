<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'promo_id',
        'total_amount',
        'status',
        'payment_proof',
        'invoice_code'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => TransactionStatus::class,
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function promo(){
        return $this->belongsTo(Promo::class);
    }

    public function transactionDetails() {
        return $this->hasMany(TransactionDetail::class);
    }
}