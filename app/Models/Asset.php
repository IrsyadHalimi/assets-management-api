<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'asset_code',
        'category_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
