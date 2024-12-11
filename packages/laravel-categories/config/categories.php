<?php

return [
    'categories_seed' => [
//        [
//            'name' => 'Parent Category',
//            'slug' => 'parent',
//            'image' => '',
//            'description' => 'Parent category disabled',
//            'parent_id' => null,
//            'enabled' => 0,
//        ],
        [
            'name' => 'Hombre',
            'slug' => 'hombre',
            'image' => 'https://cdn.siroko.com/img/sports/golf/landing-golf-image-1.jpg',
            'description' => 'Descubre una amplia selección de ropa, gafas de sol y accesorios diseñados para hombres que valoran estilo, funcionalidad y calidad. Nuestra colección combina tendencias modernas con materiales de alto rendimiento, perfectos para acompañarte en todas tus aventuras, ya sea en la ciudad, en la montaña o en la playa. Encuentra desde gafas de sol polarizadas con tecnología avanzada hasta camisetas, sudaderas y chaquetas que reflejan tu personalidad y garantizan comodidad. Con opciones versátiles y diseños únicos, cada pieza está pensada para destacar en tu día a día y en cualquier actividad al aire libre. ¡Equípate con Siroko y lleva tu estilo al siguiente nivel!',
            'parent_id' => null,
            'enabled' => 1,
        ],
        [
            'name' => 'Ciclismo',
            'slug' => 'c/ciclismo',
            'image' => 'https://cdn.siroko.com/img/sports/golf/landing-golf-image-1.jpg',
            'description' => 'Ropa y equipación de ciclismo para hombre La tecnología más avanzada aplicada a tu mayor pasión. La innovación es la clave del éxito.',
            'parent_id' => 1,
            'enabled' => 1,
        ],
        [
            'name' => 'Ofertas ciclismo hombre',
            'slug' => 'c/ofertas-especiales-ciclismo-hombre',
            'image' => 'https://cdn.siroko.com/img/sports/golf/landing-golf-image-1.jpg',
            'description' => 'Encuentra descuentos en prendas y accesorios para completar tu conjunto de ciclismo a un precio imbatible',
            'parent_id' => 2,
            'enabled' => 1,
        ],
        [
            'name' => 'Chaquetas de ciclismo para hombre',
            'slug' => 'c/ofertas-especiales-chaquetes-ciclismo-hombre',
            'image' => 'https://cdn.siroko.com/img/sports/golf/landing-golf-image-1.jpg',
            'description' => 'Prepárate para cualquier ruta con nuestras chaquetas de ciclismo diseñadas para ofrecer el máximo rendimiento y protección. Ideales para cualquier clima, nuestras chaquetas combinan materiales técnicos avanzados con un diseño ergonómico que se ajusta perfectamente a tu cuerpo, garantizando comodidad y libertad de movimiento. Explora opciones impermeables, cortavientos y térmicas, creadas para enfrentarte a lluvias inesperadas, vientos intensos o temperaturas bajas, sin sacrificar la transpirabilidad. Cada chaqueta está optimizada con detalles funcionales como bolsillos estratégicos, elementos reflectantes y ajustes precisos para acompañarte en cada pedalada. Eleva tu experiencia sobre la bici con chaquetas que combinan estilo y rendimiento en cada kilómetro. ¡Enfrenta cualquier desafío con Siroko!',
            'parent_id' => 3,
            'enabled' => 1,
        ],
        [
            'name' => 'Camisetas interiores para ciclismo',
            'slug' => 'c/ofertas-especiales-camisetas-interiores-ciclismo-hombre',
            'image' => 'https://cdn.siroko.com/img/sports/golf/landing-golf-image-1.jpg',
            'description' => 'Mantén el confort y el rendimiento en tus rutas con nuestras camisetas interiores para ciclismo, diseñadas para ser tu primera capa de protección. Estas prendas técnicas están confeccionadas con tejidos ultraligeros, transpirables y de secado rápido, que regulan la temperatura corporal y expulsan la humedad incluso en los entrenamientos más intensos. Disponibles en versiones para climas cálidos y fríos, nuestras camisetas interiores garantizan un ajuste perfecto gracias a su diseño sin costuras y su elasticidad, brindándote máxima comodidad bajo el maillot. Perfectas para optimizar tu experiencia en cada temporada del año. Equípate con la base ideal para disfrutar de cada pedalada con Siroko. ¡Comodidad y rendimiento desde la primera capa!',
            'parent_id' => 3,
            'enabled' => 1,
        ],
    ]
];
