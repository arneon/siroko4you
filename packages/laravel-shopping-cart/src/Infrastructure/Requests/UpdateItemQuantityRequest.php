<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Requests;

use App\Http\Controllers\Controller;
use Arneon\LaravelShoppingCart\Domain\Contracts\Requests\UpdateItemQuantityRequest as RequestInterface;
use Illuminate\Http\Request;

class UpdateItemQuantityRequest extends Controller implements RequestInterface
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function __invoke(): array|\Exception
    {

        try {
            $rules = [
                'cart_code' => 'required|string|size:50|exists:shopping_carts,cart_code',
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ];
            $messages = [
                'cart_code.required' => 'Shopping cart code is required',
                'cart_code.string' => 'Shopping cart code must be string',
                'cart_code.size' => 'Shopping cart code size must be of 50 characters',
                'cart_code.exists' => 'Shopping cart code does not exist',
                'product_id.required' => 'Shopping cart item is required',
                'product_id.integer' => 'Shopping cart item must be integer',
                'product_id.exists' => 'Shopping cart item does not exist',
                'quantity.required' => 'Item quantity is required',
                'quantity.integer' => 'Item quantity must be integer',
                'quantity.min' => 'Item quantity must be at least 1',
            ];
            return $this->validate($this->request, $rules, $messages);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
