<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    // protected $guarded = ['id'];
    
    protected $fillable = [
        'code',
        'discount_percent',
        'start_date',
        'end_date',
        'is_active'
    ];

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
