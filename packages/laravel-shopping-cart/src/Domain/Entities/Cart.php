<?php

namespace Arneon\LaravelShoppingCart\Domain\Entities;

class Cart
{
    private array $items = [];
    private float $totalPrice = 0.0;

    public function addItem(CartItem $item): void
    {
        if (isset($this->items[$item->getId()])) {
            $this->items[$item->getId()]->increaseQuantity($item->getQuantity());
        } else {
            $this->items[$item->getId()] = $item;
        }
        $this->recalculateTotal();
    }

    public function removeItem(string $itemId): void
    {
        unset($this->items[$itemId]);
        $this->recalculateTotal();
    }

    public function modifyItemQuantity(string $itemId, int $quantity): void
    {
        if (isset($this->items[$itemId])) {
            $this->items[$itemId]->setQuantity($quantity);
        }
        $this->recalculateTotal();
    }

    public function viewCart(): array
    {
        return $this->items;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    private function recalculateTotal(): void
    {
        $this->totalPrice = array_reduce(
            $this->items,
            fn($sum, CartItem $item) => $sum + $item->getTotalPrice(),
            0.0
        );
    }
}
