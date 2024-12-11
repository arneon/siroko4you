<?php

namespace Arneon\LaravelCategories\Infrastructure\Requests;

use App\Http\Controllers\Controller;
use Arneon\LaravelCategories\Domain\Contracts\Requests\UpdateRequest as RequestInterface;
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
                'id' => 'required|integer|exists:categories,id',
                'name' => 'required|string|min:5|max:100|unique:categories,name,'.$this->request['id'],
                'slug' => 'required|string|min:5|unique:categories,slug,'.$this->request['id'],
            ];
            $messages = [
                'id.required' => 'CategoryId is required',
                'id.integer' => 'CategoryId should be integer',
                'id.exists' => 'CategoryId does not exists',
                'name.required' => 'Name is required',
                'name.min' => 'Name must be at least 3 characters',
                'name.max' => 'Name may not be greater than 100 characters',
                'name.unique' => 'Category already exists with this name',
                'slug.required' => 'Category slug is required',
                'slug.string' => 'Category slug must be string.',
                'slug.unique' => 'Category slug already exists',
                'slug.min' => 'Category slug must be at least 5 characters',
                'enabled.required' => 'Enabled field is required',
                'enabled.integer' => 'Enabled field must be integer',
                'enabled.between' => 'Enabled field values must be 0 => false or 1 => true',
            ];
            return $this->validate($this->request, $rules, $messages);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
