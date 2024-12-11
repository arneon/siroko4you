<?php

namespace Arneon\LaravelShoppingCart\Domain\Entities;

class CartItem
{
    private string $id;
    private string $name;
    private float $price;
    private int $quantity;

    public function __construct(string $id, string $name, float $price, int $quantity = 1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTotalPrice(): float
    {
        return $this->price * $this->quantity;
    }

    public function increaseQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
