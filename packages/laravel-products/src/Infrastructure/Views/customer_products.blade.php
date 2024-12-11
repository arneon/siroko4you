<!-- resources/views/customer_products.blade.php -->
@extends('layouts.customers')
@section('content')
    <style>
        .product-card {
            margin-bottom: 20px;
        }
        .product-img {
            max-width: 200px;
            height: auto;
        }
        .product-card .btn-link {
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
        }

        .product-card .btn-link:hover {
            text-decoration: underline;
        }

        .size-box {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .size-box:hover {
            background-color: #e9ecef;
        }

        .size-box.selected {
            background-color: #007bff;
            color: #fff;
            border-color: #0056b3;
        }

        .size-label {
            font-weight: bold;
            font-size: 1rem;
        }

        .price-label {
            font-size: 0.9rem;
        }

        .d-flex.flex-wrap {
            gap: 10px;
        }
    </style>
<body>
<div class="container mt-4">
    <h1 class="text-center">Siroko Ecommerce</h1>

    <div class="row" id="product-list">
        @foreach($products as $product)
            <div class="col-md-4 product-card">
                <div class="card">
                    <img src="{{ $product['image'] }}" class="card-img-top product-img" alt="{{ $product['name'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product['name'] }}</h5>
                        @if(!empty($product['children']))

                            <form method="POST" action="/api/products">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                <input type="hidden" id="selected-size-{{ $product['id'] }}" name="selected_size">

                                <div class="mt-3">
                                    <h6>Available Sizes and Prices:</h6>
                                    <div class="d-flex flex-wrap gap-2" id="size-options-{{ $product['id'] }}">
                                        @foreach($product['children'] as $child)
                                            <div class="size-box text-center"
                                                 data-product-id="{{ $product['id'] }}"
                                                 data-size-id="{{ $child['id'] }}">
                                                <div class="size-label">{{ $child['size'] }}</div>
                                                <div class="price-label">{{ number_format($child['price'], 2) }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mt-3" disabled id="add-to-cart-{{ $product['id'] }}">Add to Cart</button>
                            </form>

                        @else
                            <p class="text-muted mt-3">No sizes available for this product.</p>
                        @endif
                        <p class="card-text"><strong>Description:</strong> {{ Str::words($product['description'], 20, '...') }}</p>
                        <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal-{{ $product['id'] }}">Leer más</button>

                    </div>
                </div>
                <!-- Modal for full description -->
                <div class="modal fade" id="descriptionModal-{{ $product['id'] }}" tabindex="-1" aria-labelledby="descriptionModalLabel-{{ $product['id'] }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="descriptionModalLabel-{{ $product['id'] }}">{{ $product['name'] }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ $product['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Error: <span id="errorMessage"></span></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sizeOptionsContainers = document.querySelectorAll('[id^="size-options-"]');

        sizeOptionsContainers.forEach(container => {
            const productId = container.id.split('-')[2];
            const sizeBoxes = container.querySelectorAll('.size-box');

            sizeBoxes.forEach(box => {
                box.addEventListener('click', function () {
                    // Check if the clicked box is already selected
                    if (this.classList.contains('selected')) {
                        // Deselect it
                        this.classList.remove('selected');

                        // Clear the hidden input value
                        const sizeInput = document.getElementById(`selected-size-${productId}`);
                        sizeInput.value = '';

                        // Disable the "Add to Cart" button
                        const addToCartButton = document.getElementById(`add-to-cart-${productId}`);
                        addToCartButton.disabled = true;
                    } else {
                        // Deselect all other size boxes for the current product
                        sizeBoxes.forEach(el => el.classList.remove('selected'));

                        // Select the clicked box
                        this.classList.add('selected');

                        // Update the hidden input value
                        const sizeInput = document.getElementById(`selected-size-${productId}`);
                        sizeInput.value = this.dataset.sizeId;

                        // Enable the "Add to Cart" button
                        const addToCartButton = document.getElementById(`add-to-cart-${productId}`);
                        addToCartButton.disabled = false;
                    }
                });
            });
        });
    });

    $(document).ready(function() {
        $('form').on('submit', function(event) {
            event.preventDefault();

            const productId = $(this).find('input[name="product_id"]').val();
            const selectedSizeId = $(this).find('input[name="selected_size"]').val();

            if (!selectedSizeId) {
                alert('Please select a size before adding to cart.');
                return;
            }

            const productName = $(this).closest('.card').find('.card-title').text();
            const sizeBox = $(`.size-box[data-size-id="${selectedSizeId}"]`);
            const price = sizeBox.find('.price-label').text();

            var itemData = {
                _token: $('input[name="_token"]').val(),
                name: productName,
                id: selectedSizeId,
                price: price,
                quantity: 1,
            };

            $.ajax({
                url: '/api/shopping-cart/',
                method: 'POST',
                data: JSON.stringify(itemData),
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer {{ $token }}'
                },
                success: function(response) {
                    alert("Product added to shopping cart");

                    console.log('Item Added', response);
                },
                error: function(error) {
                    alert(error.responseJSON.errors.message);
                    console.error('Error:', error.responseJSON.errors.message);
                }
            });
        });
    });
</script>
@endsection
