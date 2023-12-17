<?php

namespace App\Contracts\Models;

use App\Models\Category;
use Illuminate\Support\Carbon;

/**
 * @property integer id
 * @property string name
 * @property string description
 * @property integer price
 *
 * @property Carbon viewed_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon|null deleted_at
 *
 * @property Category[] $categories
 */

interface IProduct
{}
