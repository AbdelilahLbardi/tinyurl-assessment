<?php

namespace App\Models;

use App\Contracts\Models\IProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements IProduct
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'viewed_at' => 'datetime'
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->using(ProductCategory::class);    }
}
