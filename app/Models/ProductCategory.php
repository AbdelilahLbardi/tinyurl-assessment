<?php

namespace App\Models;

use App\Contracts\Models\IProductCategory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCategory extends Pivot implements IProductCategory
{
}
