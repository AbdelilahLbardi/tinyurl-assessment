<?php

namespace App\Listeners\Product;

use App\Actions\Product\GenerateProducts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateProducts
{
    private GenerateProducts $generateProducts;

    public function __construct(GenerateProducts $generateProducts)
    {
        $this->generateProducts = $generateProducts;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if ($event instanceof \App\Jobs\Product\GenerateProducts::class)
        {
            $this->generateProducts->execute();
        }
    }
}
