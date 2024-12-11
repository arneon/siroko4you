<?php

namespace Arneon\LaravelProducts\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Arneon\LaravelCategories\Infrastructure\Models\Category;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'sku',
        'slug',
        'name',
        'description',
        'parent_id',
        'category_id',
        'enabled',
        'image',
        'size',
        'color',
        'price',
        'stock',
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'category_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(Product::class, 'id', 'parent_id');
    }
}

