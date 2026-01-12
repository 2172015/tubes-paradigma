<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price_at_purchase',
    ];

    public function product() {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
