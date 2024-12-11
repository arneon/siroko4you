<?php

namespace Arneon\LaravelUsers\Infrastructure\Controllers;

use Arneon\LaravelUsers\Infrastructure\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Arneon\LaravelUsers\Application\UseCases\RegisterUseCase;
use Arneon\LaravelUsers\Infrastructure\Requests\RegisterRequest;
use Arneon\LaravelUsers\Application\UseCases\ValidateLoginUseCase;
use Arneon\LaravelUsers\Application\UseCases\FindAllUseCase;
use Arneon\LaravelUsers\Application\UseCases\FindByFieldUseCase;
use Arneon\LaravelUsers\Application\UseCases\CreateUseCase;
use Arneon\LaravelUsers\Infrastructure\Requests\CreateRequest;
use Arneon\LaravelUsers\Application\UseCases\UpdateUseCase;
use Arneon\LaravelUsers\Infrastructure\Requests\UpdateRequest;
use Arneon\LaravelUsers\Application\UseCases\DeleteUseCase;
use Arneon\LaravelUsers\Infrastructure\Requests\DeleteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController
{
    public function __construct(
        private readonly FindAllUseCase $findAllUseCase,
        private readonly FindByFieldUseCase $findByFieldUseCase,
        private readonly CreateUseCase $createUseCase,
        private readonly UpdateUseCase $updateUseCase,
        private readonly DeleteUseCase  $deleteUseCase,
        private readonly RegisterUseCase $registerUseCase,
        private readonly ValidateLoginUseCase  $validateLoginUseCase,
    )
    {
    }

    public function register(Request $request) : JsonResponse
    {
        try{
            $registerRequest = new RegisterRequest($request);
            $validatedData = $registerRequest->__invoke();
        }
        catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }

        try {
            return response()->json(['data' => $this->registerUseCase->__invoke($validatedData)]);
        }catch (\Exception $e)
        {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 500);
        }
    }

    public function loginForm(Request $request)
    {
        $login_redirect_endpoint = env('LOGIN_REDIRECT_ENDPOINT', '/');
        return view('arneon/laravel-users::login', compact('login_redirect_endpoint'));
    }

    public function login(Request $request) : JsonResponse|RedirectResponse
    {
        try{
            $loginRequest = new LoginRequest($request);
            $validatedData = $loginRequest->__invoke();
        }
        catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }

        try {
            $user = $this->validateLoginUseCase->__invoke($request['email'], $request['password']);
            return response()->json(['data' => "Usuario logueado"]);
        }catch(\Exception $e)
        {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }
    }
    public function findAll() : JsonResponse
    {
        try {
            return response()->json(['data' => $this->findAllUseCase->__invoke()]);
        }catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }
    }

    public function findByField(mixed $fieldValue, string $field): JsonResponse
    {
        try {
            return response()->json(['data' => $this->findByFieldUseCase->__invoke($fieldValue, $field)]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }
    }
    public function create(Request $request) : JsonResponse
    {
        try{
            $createRequest = new CreateRequest($request);
            $validatedData = $createRequest->__invoke();
        }
        catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }

        try {
            return response()->json(['data' => $this->createUseCase->__invoke($validatedData)]);
        }catch (\Exception $e)
        {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 500);
        }
    }
    public function update(Request $request, int $id) : JsonResponse
    {
        try {
            $updateRequest = new UpdateRequest($id, $request);
            $validatedData = $updateRequest->__invoke();
        }catch (\Exception $e)
        {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }

        try {
            return response()->json(['data' => $this->updateUseCase->__invoke($id, $validatedData)]);
        }catch(\Exception $e)
        {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 500);
        }
    }
    public function delete(mixed $id, Request $request) : JsonResponse
    {
        try{
            $deleteRequest = new DeleteRequest($id, $request);
            $validatedData = $deleteRequest->__invoke();
        }
        catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }

        try {
            return response()->json(['data' => $this->deleteUseCase->__invoke($id)]);
        }catch(\Exception $e)
        {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }
    }


}
