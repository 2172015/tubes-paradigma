<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    // protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'message',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
