<?php

namespace App\Actions\Product;


use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

class GetProducts {

    const SORTERS = [
        'name', 'price'
    ];

    public function execute(array $filters = []): Collection|CursorPaginator
    {
        $query = Product::withTrashed()
            ->select('id', 'name', 'price', 'deleted_at')
            ->with('categories:id,name')
            ->orderBy('products.deleted_at');

        foreach (self::SORTERS as $sorter) {

            $order = strtoupper($filters[$sorter] ?? null);

            if (in_array($order, ['ASC', 'DESC'])) {
                $query->orderBy($sorter, $order);
            }

        }

        if ($filters['last-viewed'] ?? null) {
            return $query->whereNotNull('viewed_at')
                ->orderByDesc('viewed_at')
                ->limit(10)
                ->get();
        }

        return $query->cursorPaginate(100);
    }

}
