<!-- resources/views/customer_shopping-cart.blade.php -->
@extends('layouts.customers')
@section('content')
    <style>
        .total-shopping-cart {
            text-align: right;
            margin-top: 20px;
        }

        .checkout-btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
<body>
<div class="container">
    <h1 class="my-4">Shopping Cart</h1>
    @if(!empty($cart_items))
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>

        @foreach($cart_items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ number_format($item['price'], 2) }}</td>
                <td>
                    <form class="updateItemQuantityForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="cart_code" value="{{ $cart['cart_code'] }}">
                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control d-inline-block" style="width: 70px;" data="">
                        <button type="submit" class="btn btn-sm btn-primary">Update Qty.</button>
                    </form>
                </td>
                <td>{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                <td>
                    <button class="btn btn-sm btn-danger buttonRemoveItem"
                            data-cart-code="{{ $cart['cart_code'] }}"
                            data-product-id="{{ $item['id'] }}"
                            data-product-name="{{ $item['name'] }}"
                    >
                        Remove Item
                    </button>
                </td>
            </tr>
        @endforeach
            </tbody>
        </table>
        <div class="total-shopping-cart">
            <strong>Total: {{ number_format($cart['total_price'], 2) }}</strong>
        </div>
        <div class="checkout-btn-container">
            <a href="shopping-cart/checkout/{{ $cart['cart_code']  }}" class="btn btn-success btn-lg">Proceed to Checkout</a>
        </div>
    </div>
    @endif
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
    $(document).ready(function(){
        $(".buttonRemoveItem").on('click', function() {
            var cart_code = $(this).data('cart-code');
            var product_id = $(this).data('product-id');
            var product_name = $(this).data('product-name');
            var formData = {
                _token: $('input[name="_token"]').val(),
                cart_code: $(this).data('cart_code'),
                product_id: $(this).data('product_id'),
            };

            if(confirm(`Are you sure to delete this item from shopping cart`))
            {
                $.ajax({
                    url: `/api/shopping-cart/${cart_code}/${product_id}`,
                    method: 'PUT',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer {{ $token }}'
                    },
                    success: function(response) {
                        console.log('Item removed from shopping cart:', response);
                        location.reload();
                    },
                    error: function(error) {
                        alert(error.responseJSON.errors.message);
                        console.error('Error:', error.responseJSON.errors.message);
                    }
                });
            }
        });


        $(document).on('submit', '.updateItemQuantityForm', function(event) {
            event.preventDefault();

            var form = $(this);
            var cart_code = form.find('input[name="cart_code"]').val();
            var product_id = form.find('input[name="product_id"]').val();
            var quantity = form.find('input[name="quantity"]').val();

            var formData = {
                cart_code: cart_code,
                product_id: product_id,
                quantity: quantity,
            };

            $.ajax({
                url: `/api/shopping-cart/${cart_code}/${product_id}/${quantity}`,
                method: 'PUT',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer {{ $token }}'
                },
                success: function(response) {
                    console.log('Item quantity updated successfully:', response);
                    location.reload();
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

