<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // HAPUS baris di bawah ini agar created_at & updated_at otomatis terisi
    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'promo_id',
        'total_amount',
        'status',
        'payment_proof',
        'invoice_code'
    ];

    // TAMBAHAN: Casts agar tipe data lebih presisi saat ditarik dari DB
    protected $casts = [
        'total_amount' => 'decimal:2', // Agar dianggap angka desimal, bukan string
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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