<?php

namespace App\Contracts\Models;

use App\Models\Product;
use Illuminate\Support\Carbon;

/**
 * @property integer id
 * @property string name
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Product[] $products
 */

interface ICategory
{}
