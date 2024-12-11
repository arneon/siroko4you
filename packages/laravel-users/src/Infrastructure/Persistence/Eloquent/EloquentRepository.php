<?php

namespace Arneon\LaravelUsers\Infrastructure\Persistence\Eloquent;

use Arneon\LaravelUsers\Domain\Repositories\Repository as RepositoryInterface;
use Arneon\LaravelUsers\Infrastructure\Models\User as Model;
use Arneon\LaravelUsers\Infrastructure\Helpers\UserHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class EloquentRepository implements RepositoryInterface
{
    use UserHelper;

    public function findByField(mixed $fieldValue, string $field = 'id')
    {
        try {
            $user = new Model();
            $fields = array_merge($user->getFillable(), ['id']);
            if(!in_array($field, $fields))
            {
                throw new \Exception('Field does not exist');
            }

            $user = $user->where($field, $fieldValue)->first();
            return $user ? $user->toArray() : [];
        }
        catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function findAll()
    {
        return Model::all()->toArray();
    }

    public function create(array $data) : array|\Exception
    {
        try {
            $model = $this->setEntityValuesToModel(new Model(), $data);
            $model->save();
            return $model->toArray();
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function update($id, array $data) : array|\Exception
    {
        $model = Model::where('id', $id)->first();
        if($model)
        {
            $model = $this->setEntityValuesToModel($model, $data);
            $model->save();
            return $model->toArray();
        }
        else
        {
            throw new \Exception('User not found', 404);
        }
    }

    public function delete($id)
    {
        $model = Model::where('id', $id)->first();
        if($model)
        {
            $model->delete();
            return ['message' => 'User deleted successfully.'];
        }
        else
        {
            throw new \Exception('User not found', 404);
        }
    }

    public function register(array $data) : array|\Exception
    {
        try {
            $model = $this->setEntityValuesToModel(new Model(), $data);
            $model->save();
            $token = $model->createToken('api');
            return ['token' => $token->plainTextToken];
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function validateLogin(string $email, string $password)
    {
        try {
            $user = Model::where('email', $email)->first();
            if(!$user)
            {
                throw new \Exception('User not found', 404);
            }

            if(!Auth::attempt(['email' => $email, 'password' => $password]))
            {
                throw new \Exception('Wrong email or password', '400');
            }

            request()->session()->regenerate();
            request()->session()->put(['user_id' => $user->id]);

            return $user;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}

