<?php

namespace App\Contracts\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Carbon;

/**
 * @property integer category_id
 * @property integer product_id
 *
 * @property Product $product
 * @property Category $category
 */

interface IProductCategory
{}
