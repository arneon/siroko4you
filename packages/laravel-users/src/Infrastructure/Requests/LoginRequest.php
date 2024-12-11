<?php

namespace Arneon\LaravelUsers\Infrastructure\Requests;

use App\Http\Controllers\Controller;
use Arneon\LaravelUsers\Domain\Contracts\Requests\LoginRequest as RequestInterface;
use Illuminate\Http\Request;

class LoginRequest extends Controller implements RequestInterface
{
    private Request $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function __invoke(): array|\Exception
    {
        try {
            $rules = [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8',
            ];
            $messages = [
                'email.required' => 'email param is required',
                'email.email' => 'email param format is incorrect',
                'exists.exists' => 'given email param does not exist',
                'password.required' => 'password param is required',
                'password.min' => 'password param must be at least 8 characters',
            ];
            return $this->validate($this->request, $rules, $messages);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
