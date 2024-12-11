<?php

namespace Arneon\LaravelCategories\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Arneon\LaravelCategories\Application\UseCases\FindAllUseCase;
use Arneon\LaravelCategories\Application\UseCases\FindByIdUseCase;
use Arneon\LaravelCategories\Application\UseCases\CreateUseCase;
use Arneon\LaravelCategories\Infrastructure\Requests\CreateRequest;
use Arneon\LaravelCategories\Application\UseCases\UpdateUseCase;
use Arneon\LaravelCategories\Infrastructure\Requests\UpdateRequest;
use Arneon\LaravelCategories\Application\UseCases\DeleteUseCase;
use Arneon\LaravelCategories\Infrastructure\Requests\DeleteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Arneon\MongodbUserLogs\Application\Facade\MongodbLogFacade;

class Controller
{
    public function __construct(
        private readonly FindByIdUseCase $findByIdUseCase,
        private readonly FindAllUseCase $findAllUseCase,
        private readonly CreateUseCase $createUseCase,
        private readonly UpdateUseCase $updateUseCase,
        private readonly DeleteUseCase  $deleteUseCase,
    )
    {
    }

    public function list()
    {
        try {
            return response()->json(['data' => $this->findAllUseCase->__invoke()]);
        }catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }
    }

    public function find(int $id)
    {
        try {
            return response()->json(['data' => $this->findByIdUseCase->__invoke($id)]);
        }catch (\Exception $e){
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
            return response()->json(['data' => $this->createUseCase->__invoke($request->all())]);
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
            return response()->json(['data' => $this->updateUseCase->__invoke($id, $request->all())]);
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


    public function adminCategories()
    {
        $categories = $this->findAllUseCase->__invoke();
        $server_fqdn = env('SERVER_FQDN');
        $company_name = env('COMPANY_NAME', 'Test Company');
        $token = env('BEARER_TOKEN');
        return view('arneon/laravel-categories::admin_categories', compact(['categories', 'server_fqdn', 'company_name', 'token']));
    }
}
