<?php

namespace App\Models;

use App\Contracts\Models\ICategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model implements ICategory
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->using(ProductCategory::class);
    }
}
