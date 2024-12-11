<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Arneon\LaravelShoppingCart\Application\UseCases\FindByFieldUseCase;
use Arneon\LaravelShoppingCart\Infrastructure\Requests\AddItemRequest;
use Arneon\LaravelShoppingCart\Application\UseCases\AddItemUseCase;
use Arneon\LaravelShoppingCart\Infrastructure\Requests\RemoveItemRequest;
use Arneon\LaravelShoppingCart\Application\UseCases\RemoveItemUseCase;
use Arneon\LaravelShoppingCart\Infrastructure\Requests\UpdateItemQuantityRequest;
use Arneon\LaravelShoppingCart\Application\UseCases\UpdateItemQuantityUseCase;

use Arneon\LaravelShoppingCart\Infrastructure\Requests\UpdateRequest;
use Arneon\LaravelShoppingCart\Infrastructure\Requests\DeleteRequest;


class Controller
{
    public function __construct(
        private readonly FindByFieldUseCase $findByFieldUseCase,
        private readonly AddItemUseCase $addItemUseCase,
        private readonly RemoveItemUseCase  $removeItemUseCase,
        private readonly UpdateItemQuantityUseCase $updateItemQuantityUseCase,
    )
    {
    }

    public function show()
    {
        $cart = [];
        $cart_items = [];

        if(array_key_exists('_token', request()->session()->all()))
        {
            $session_id = request()->session()->get('_token');
            $cart = $this->findByFieldUseCase->__invoke('session_id', $session_id);
            if(!empty($cart))
            {
                $cart_items = json_decode($cart['items'], true);
            }
        }

        $server_fqdn = env('SERVER_FQDN');
        $company_name = env('COMPANY_NAME', 'Test Company');
        $token = env('BEARER_TOKEN');
        return view('arneon/laravel-shopping_cart::customer_shopping-cart', compact(['cart', 'cart_items', 'server_fqdn', 'company_name', 'token']));
    }

    public function checkout()
    {
        $cart = [];
        $cart_items = [];

        if(array_key_exists('_token', request()->session()->all()))
        {
            $session_id = request()->session()->get('_token');
            $cart = $this->findByFieldUseCase->__invoke('session_id', $session_id);
            if(!empty($cart))
            {
                $cart_items = json_decode($cart['items'], true);
            }
        }

        $server_fqdn = env('SERVER_FQDN');
        $company_name = env('COMPANY_NAME', 'Test Company');
        $token = env('BEARER_TOKEN');
        return view('arneon/laravel-shopping_cart::checkout_1', compact(['cart', 'cart_items', 'server_fqdn', 'company_name', 'token']));
    }

    public function addItem(Request $request) : JsonResponse
    {
        try{
            $methodRequest = new AddItemRequest($request);
            $validatedData = $methodRequest->__invoke();
        }
        catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }

        try {
            $response = response()->json(['data' => $this->addItemUseCase->__invoke($request->all())]);
            $data = json_decode($response->getContent(), true);
            setcookie('cart_code', $data['data']['cart_code'], time() + 3600*24*30);
            return $response;
        }catch (\Exception $e)
        {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 500);
        }
    }

    public function removeItem(string $cart_code, int $product_id, Request $request) : JsonResponse
    {
        $requestArray = ['cart_code' => $cart_code, 'product_id' => $product_id];
        $request->merge($requestArray);

        try{
            $methodRequest = new RemoveItemRequest($request);
            $validatedData = $methodRequest->__invoke();
        }
        catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }

        try {
            return response()->json(['data' => $this->removeItemUseCase->__invoke($requestArray)]);
        }catch (\Exception $e)
        {
            return response()->json(['errors' => ['message' => $e->getMessage()]], 500);
        }
    }

    public function updateItemQuantity(string $cart_code, int $product_id, int $quantity, Request $request) : JsonResponse
    {
        $requestArray = ['cart_code' => $cart_code, 'product_id' => $product_id, 'quantity' => $quantity];
        $request->merge($requestArray);

        try{
            $methodRequest = new UpdateItemQuantityRequest($request);
            $validatedData = $methodRequest->__invoke();
        }
        catch (\Exception $e){
            return response()->json(['errors' => ['message' => $e->getMessage()]], 400);
        }

        try {
            return response()->json(['data' => $this->updateItemQuantityUseCase->__invoke($requestArray)]);
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
            return response()->json(['data' => $this->modifyItemQuantityUseCase->__invoke($id, $request->all())]);
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
