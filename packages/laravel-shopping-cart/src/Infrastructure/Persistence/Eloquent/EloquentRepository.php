<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Persistence\Eloquent;

use Arneon\LaravelShoppingCart\Domain\Repositories\Repository as RepositoryInterface;
use Arneon\LaravelShoppingCart\Infrastructure\Models\ShoppingCart as Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class EloquentRepository implements RepositoryInterface
{
    public function addItem(array $data)
    {
        $currentCart = $this->findByField($data['_token'], 'session_id');

        if(empty($currentCart))
        {
            $cart = $this->buildNewCart($data);
            return $this->create($cart);
        }
        else
        {
            $cart = $currentCart;
            $cart['items'] = $this->rebuildCartItems($cart, $data);
            $cart['total_price'] = $this->recalculateTotalPrice($cart['items']);
            $cart['items'] = json_encode($cart['items']);

            return $this->update($cart['id'], $cart);
        }
    }

    public function removeItem(array $data)
    {
        $currentCart = $this->findByField($data['cart_code'], 'cart_code');
        if(!empty($currentCart))
        {
            $cart = $currentCart;
            $cart['items'] = $this->removeCartItem(json_decode($cart['items'], true), $data['product_id']);
            $cart['total_price'] = $this->recalculateTotalPrice($cart['items']);
            $cart['items'] = json_encode($cart['items']);

            return $this->update($cart['id'], $cart);
        }
    }

    public function updateItemQuantity(array $data)
    {
        $currentCart = $this->findByField($data['cart_code'], 'cart_code');

        if(!empty($currentCart))
        {
            $cart = $currentCart;
//            $cart['items'] = $this->updateCartItemQuantity(json_decode($cart['items'], true), $data['product_id'], $data['quantity']);
            $cart['items'] = $this->rebuildCartItems($cart, $data, 'product_id');
            $cart['total_price'] = $this->recalculateTotalPrice($cart['items']);
            $cart['items'] = json_encode($cart['items']);

            return $this->update($cart['id'], $cart);
        }
    }

    private function removeCartItem(array $items, int $product_id)
    {
        $newItems = array_filter($items, function ($item) use ($product_id) {
            return $item['id'] <> $product_id;
        });

        return array_values($newItems);
    }

    private function buildNewCart($data) : array
    {
        $cart['cart_code'] = $this->generateCartCode();
        $cart['session_id'] = $data['_token'];
        $cart['total_price'] = $data['price'];
        $cart['items'] = json_encode ([[
            'id' => $data['id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
        ]]);
        return $cart;
    }

    private function rebuildCartItems($currentCart, $data, $dataIndexName='id')
    {
        $items = json_decode($currentCart['items'], true);

        $index = array_search($data[$dataIndexName], array_column($items, 'id'));

        if($index === false)
        {
            $newItem = [
                'id' => $data['id'],
                'name' => $data['name'],
                'price' => $data['price'],
                'quantity' => $data['quantity'],
            ];
            array_push($items, $newItem);
        }
        else{
            $items[$index]['quantity'] = ($data['quantity'] > 0 ? $data['quantity'] : 1) ;
        }
        return $items;
    }

    private function recalculateTotalPrice($items)
    {
        $total_price = 0.0;
        foreach($items as $item)
        {
            $total_price += $item['price'] * $item['quantity'];
        }

        return $total_price;
    }

    private function generateCartCode()
    {
        $code = Str::random(50);
        $row = $this->findByField($code, 'cart_code');
        if(!$row) return $code;
        else $this->generateCartCode();
    }

    public function findByField(mixed $fieldValue, mixed $field = 'id')
    {
        try {
            $model = new Model();
            $fields = array_merge($model->getFillable(), ['id']);
            if(!in_array($field, $fields))
            {
                throw new \Exception('Field  does not exist');
            }

            $model = $model->where($field, $fieldValue)->first();
            return $model ? $model->toArray() : [];
        }
        catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function findAll()
    {
        return Model::all();
    }
    public function findById(int $id)
    {
        $model = Model::where('id', $id)->first();

        if($model)
        {
            $model = $model->toArray();
            return $model;
        }
        else{
            return [];
        }
    }

    public function create(array $data)
    {
        try {
            $model = new Model();
            $model->fill($data);
            $model->save();
            return $model->toArray();
        }catch (\Exception $e){
                throw new \Exception($e->getMessage());
        }
    }

    public function update($id, array $data)
    {
        $model = Model::where('id', $id)->first();
        if($model)
        {
            try {
                $model->fill($data);
                $model->save();
                return $model->toArray();
            }catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
        else
        {
            throw new \Exception('Product not found', 404);
        }
    }

    public function delete($id)
    {
        $model = Model::where('id', $id)->first();
        if($model)
        {
            $model->delete();
            return ['message' => 'Product deleted successfully.'];
        }
        else
        {
            throw new \Exception('Product not found', 404);
        }
    }
}

