<?php

namespace Arneon\LaravelUsers\Infrastructure\Requests;

use App\Http\Controllers\Controller;
use Arneon\LaravelUsers\Domain\Contracts\Requests\UpdateRequest as RequestInterface;
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
                'id' => 'required|integer|exists:users,id',
                'name' => 'required|string|min:3|max:100',
                'email' => 'required|string|email|max:100|unique:users,email,'.$this->request['id'],
                'password' => 'required|string|min:8|max:30',
            ];
            $messages = [
                'id.required' => 'id is required',
                'id.integer' => 'id is integer',
                'id.exists' => 'id is invalid',
                'name.required' => 'Name is required',
                'name.min' => 'Name must be at least 3 characters',
                'name.max' => 'Name may not be greater than 100 characters',
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a valid email address',
                'email.unique' => 'Email is already in use',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 8 characters',
                'password.max' => 'Password may not be greater than 30 characters',
            ];
            return $this->validate($this->request, $rules, $messages);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
