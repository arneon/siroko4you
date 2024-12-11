<?php

return [
    'shopping_carts_seed' => [
        [
            'id' => 1,
            'cart_code' => 'SC010203040506070809101112131415161718192025222324',
            'session_id' => 'p/maillot-ciclismo-manga-larga-hombre-m2-gravel',
            'items' => json_encode([
                ['product_id' => 2, 'quantity' => 1, 'price' => 10.00],
                ['product_id' => 4, 'quantity' => 1, 'price' => 20.00],
            ]),
            'total_price' => 20.00,
            'status' => 0,
            'customer_id' => null,
        ],
    ]
];
