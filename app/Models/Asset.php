<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'asset_code',
        'category',
        'location',
        'description',
        'price',
        'quantity',
        'amount',
        'established_at',
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
        'amount' => 'float',
        'established_at' => 'date',
    ];
}
