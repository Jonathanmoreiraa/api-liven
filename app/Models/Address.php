<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'street', 'number', 'neighborhood', 'additional', 'city', 'state', 'country', 'postal_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
