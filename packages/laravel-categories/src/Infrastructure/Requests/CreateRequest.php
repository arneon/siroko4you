<?php

namespace Arneon\LaravelCategories\Infrastructure\Requests;

use App\Http\Controllers\Controller;
use Arneon\LaravelCategories\Domain\Contracts\Requests\CreateRequest as RequestInterface;
use Illuminate\Http\Request;

class CreateRequest extends Controller implements RequestInterface
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
                'name' => 'required|string|min:5|max:100|unique:categories,name',
                "slug" => "required|string|min:5|unique:categories,slug",
                "enabled" => "required|integer|between:0,1",
                "parent_id" => "nullable|integer|exists:categories,id",
            ];

            $messages = [
                'name.string' => 'Category name must be string.',
                'name.required' => 'Category name is required',
                'name.unique' => 'Category name already exists',
                'name.min' => 'Category name must be at least 3 characters',
                'name.max' => 'Category name may not be greater than 100 characters',
                'slug.required' => 'Category slug is required',
                'slug.string' => 'Category slug must be string.',
                'slug.unique' => 'Category slug already exists',
                'slug.min' => 'Category slug must be at least 5 characters',
                'enabled.required' => 'Enabled field is required',
                'enabled.integer' => 'Enabled field must be integer',
                'enabled.between' => 'Enabled field values must be 0 => false or 1 => true',
                'parent_id.exists' => 'Category parent id does not exist',
            ];

            return $this->validate($this->request, $rules, $messages);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
