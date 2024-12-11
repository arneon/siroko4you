<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Requests;

use App\Http\Controllers\Controller;
use Arneon\LaravelShoppingCart\Domain\Contracts\Requests\AddItemRequest as RequestInterface;
use Illuminate\Http\Request;

class AddItemRequest extends Controller implements RequestInterface
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
                'id' => 'required|integer|exists:products,id',
                'name' => 'required|string',
                "price" => "required|numeric|gt:0",
                "quantity" => "required|integer|min:1",
            ];

            $messages = [
                'id.required' => 'Item id is required.',
                'id.integer' => 'Item id must be an integer.',
                'id.exists' => 'Item id does not exist.',
                'name.required' => 'Item name is required',
                'name.string' => 'Item name must be string.',
                'price.required' => 'Item price is required',
                'price.numeric' => 'Item price must be decimal number.',
                'price.gt' => 'Item price must be > 0.',
                'quantity.required' => 'Item quantity is required.',
                'quantity.integer' => 'Item quantity must be integer.',
                'quantity.min' => 'Item quantity must be integer > 0.',
            ];

            return $this->validate($this->request, $rules, $messages);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
