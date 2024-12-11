<?php

namespace Arneon\LaravelProducts\Infrastructure\Requests;

use App\Http\Controllers\Controller;
use Arneon\LaravelProducts\Domain\Contracts\Requests\UpdateRequest as RequestInterface;
use Illuminate\Http\Request;

class UpdateRequest extends Controller implements RequestInterface
{
    private Request $request;
    public function __construct(int $id, Request $request)
    {
        $request->merge(['id' => $id ]);
        $this->request = $request;
    }

    public function __invoke(): array|\Exception
    {
        try {
            $rules = [
                'id' => 'required|integer|exists:products,id',
                'name' => 'required|string|min:5|max:100|unique:products,name,'.$this->request['id'],
                'slug' => 'required|string|min:5|unique:products,slug,'.$this->request['id'],
                'sku' => 'required|string|min:3|max:20|unique:products,sku,'.$this->request['id'],
                "image" => "required|string",
                "category_id" => "required|integer|exists:categories,id",
                "parent_id" => "nullable|integer|gt:0|exists:products,id",
                "price" => [
                    'required_unless:parent_id,null',
                    'min:0',
                    'exclude_if:parent_id,null',
                    'decimal:0,2',
                    'gt:0'
                    ],
                "stock" => [
                    'required_unless:parent_id,null',
                    'min:0',
                    'exclude_if:parent_id,null',
                    'integer',
                    'gt:0'
                ],
            ];
            $messages = [
                'id.required' => 'ProductId is required',
                'id.integer' => 'ProductId must be integer',
                'id.exists' => 'ProductId does not exists',
                'sku.string' => 'Product SKU must be string.',
                'sku.required' => 'Product SKU is required',
                'sku.unique' => 'Product SKU already exists',
                'sku.min' => 'Product SKU must be at least 3 characters',
                'sku.max' => 'Product SKU may not be greater than 20 characters',
                'name.required' => 'Product name is required',
                'name.string' => 'Product name must be string.',
                'name.unique' => 'Product name already exists',
                'name.min' => 'Product name must be at least 5 characters',
                'name.max' => 'Product name may not be greater than 100 characters',
                'slug.required' => 'Product slug is required',
                'slug.string' => 'Product slug must be string.',
                'slug.unique' => 'Product slug already exists',
                'slug.min' => 'Product slug must be at least 5 characters',
                'enabled.required' => 'Enabled field is required',
                'enabled.integer' => 'Enabled field must be integer',
                'enabled.between' => 'Enabled field values must be 0 => false or 1 => true',
                'parent_id.exists' => 'Product parent id does not exist',
                'category_id.required' => 'Product category is required',
                'price.required_if' => 'Price is required',
                'stock.required_if' => 'Stock is required',
            ];
            return $this->validate($this->request, $rules, $messages);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
