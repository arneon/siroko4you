<?php

namespace Arneon\LaravelShoppingCart\Application\Console\Commands;

use Illuminate\Console\Command;
use Arneon\LaravelShoppingCart\Infrastructure\Models\ShoppingCart as Model;
use Arneon\LaravelShoppingCart\Application\UseCases\AddItemUseCase;

class ShoppingCartInit extends Command
{
    protected $signature = 'cart:init';
    protected $description = 'Initializes shopping cart';

    public function __construct(
        private readonly AddItemUseCase $addItemUseCase,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $rows = config('arneon-shopping-carts.shopping_carts_seed');
        if ($rows) {
            foreach ($rows as $item) {
                $row = Model::where('cart_code', $item['cart_code'])->first();
                if(!$row) {
                    $this->addItemUseCase->__invoke($item);
                }
            }
        }
    }
}
