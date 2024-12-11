<?php

namespace Arneon\LaravelProducts\Application\Console\Commands;

use Illuminate\Console\Command;
use Arneon\LaravelProducts\Infrastructure\Models\Product as Model;
use Arneon\LaravelProducts\Application\UseCases\CreateUseCase;

class ProductInit extends Command
{
    protected $signature = 'products:init';
    protected $description = 'Initializes products';

    public function __construct(
        private readonly CreateUseCase $createUseCase,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $products_seed = config('arneon-products.products_seed');
        if ($products_seed) {
            foreach ($products_seed as $product) {
                $row = Model::where('name', $product['name'])->first();
                if(!$row) {
                    $this->createUseCase->__invoke($product);
                }
            }
        }

    }
}
