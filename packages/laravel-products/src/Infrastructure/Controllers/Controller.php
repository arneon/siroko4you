<?php

namespace Arneon\LaravelProducts\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Arneon\LaravelCategories\Application\UseCases\FindAllUseCase as CategoryFindAllUseCase;
use Arneon\LaravelProducts\Application\UseCases\FindAllUseCase;
use Arneon\LaravelProducts\Application\UseCases\FindAllByFieldUseCase;
use Arneon\LaravelProducts\Application\UseCases\FindByIdUseCase;
use Arneon\LaravelProducts\Application\UseCases\CreateUseCase;
use Arneon\LaravelProducts\Infrastructure\Requests\CreateRequest;
use Arneon\LaravelProducts\Application\UseCases\UpdateUseCase;
use Arneon\LaravelProducts\Infrastructure\Requests\UpdateRequest;
use Arneon\LaravelProducts\Application\UseCases\DeleteUseCase;
use Arneon\LaravelProducts\Infrastructure\Requests\DeleteRequest;
use Illuminate\Http\Request;

class Controller
{
    public function __construct(
        private readonly CategoryFindAllUseCase $categoryFindAllUseCase,
        private readonly FindAllByFieldUseCase $findAllByFieldUseCase,
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
        $products = $this->findAllUseCase->__invoke();
        $server_fqdn = env('SERVER_FQDN');
        $company_name = env('COMPANY_NAME', 'Test Company');
        $token = env('BEARER_TOKEN');
        return view('arneon/laravel-products::customer_products', compact(['products', 'server_fqdn', 'company_name', 'token']));
    }

    public function findAll()
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


    public function adminProducts()
    {
        $categories = $this->categoryFindAllUseCase->__invoke();
        $products = $this->findAllByFieldUseCase->__invoke('parent_id', null);
        $parentProducts = $products;
        array_push($parentProducts, ['id' => null, 'name' => 'Parent Product']);
        foreach ($products as &$product) {
            $product['children'] = $this->findAllByFieldUseCase->__invoke('parent_id', $product['id']);
        }

        $server_fqdn = env('SERVER_FQDN');
        $company_name = env('COMPANY_NAME', 'Test Company');
        $token = env('BEARER_TOKEN');
        return view('arneon/laravel-products::admin_products', compact(['categories', 'products', 'parentProducts', 'server_fqdn', 'company_name', 'token']));
    }
}
