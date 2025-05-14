<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public static function countPerCategory()
    {
        return self::select('category_id', DB::raw('count(*) as total'))
            ->with('category:id,name')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name ?? 'Tanpa Kategori',
                    'total' => $item->total
                ];
            });
    }
}
