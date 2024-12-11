<!-- resources/views/products.blade.php -->
@extends('layouts.app')
@section('content')
    <style>
        .product-card {
            margin-bottom: 20px;
        }
        .product-img {
            max-width: 100%;
            height: auto;
        }
        .modal-backdrop.show {
            z-index: 1040; /* Make sure modals are on top */
        }
        .modal.show {
            z-index: 1050; /* Make sure modals are on top */
        }
    </style>
<body>

<div class="container mt-5">
    <h1 class="text-center">Product List Admin</h1>
    <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productModal" id="addProductBtn">Add new product</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Sku</th>
                <th>Name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @php
                $parentProductIndex=1;
            @endphp
            @foreach($products as $product)
                <tr>
                    <td>{{ $parentProductIndex }}</td>
                    <td><strong>{{ $product['sku'] }}</strong></td>
                    <td><strong>{{ $product['name'] }}</strong></td>
                    <td>{{ !$product['parent_id'] ? 'Parent' : 'Child' }}</td>
                    <td>{{ number_format($product['price'], 2) }}</td>
                    <td>{{ $product['stock'] }}</td>
                    <td><button class="btn btn-primary editProductBtn buttonLoadFormEditProduct"  data-bs-toggle="modal" data-bs-target="#productModal"
                                data-id="{{ $product['id'] }}"
                                data-sku="{{ $product['sku'] }}"
                                data-name="{{ $product['name'] }}"
                                data-parent_id="{{ $product['parent_id'] }}"
                                data-category_id="{{ $product['category_id'] }}"
                                data-description="{{ $product['description'] }}"
                                data-slug="{{ $product['slug'] }}"
                                data-image="{{ $product['image'] }}"
                                data-size="{{ $product['size'] }}"
                                data-color="{{ $product['color'] }}"
                                data-price="{{ number_format($product['price'], 2) }}"
                                data-stock="{{ $product['stock'] }}"
                                data-enabled="{{ $product['enabled'] }}"
                        >
                            Edit
                        </button>
                    </td>
                    <td><button class="btn btn-danger buttonDeleteProduct"
                                data-id="{{ $product['id'] }}"
                                data-name="{{ $product['name'] }}"
                        >
                            Delete
                        </button></td>
                </tr>
                @php
                    $childProductIndex=1;
                @endphp
                @foreach($product['children'] as $child)
                    <tr>
                        <td>{{ $parentProductIndex }} - {{ $childProductIndex }}</td>
                        <td><strong>{{ $child['sku'] }}</strong></td>
                        <td><strong>{{ $child['name'] }}</strong></td>
                        <td>{{ !$child['parent_id'] ? 'Parent' : 'Child' }}</td>
                        <td>{{ number_format($child['price'], 2) }}</td>
                        <td>{{ $child['stock'] }}</td>
                        <td><button class="btn btn-primary editProductBtn buttonLoadFormEditProduct"  data-bs-toggle="modal" data-bs-target="#productModal"
                                    data-id="{{ $child['id'] }}"
                                    data-sku="{{ $child['sku'] }}"
                                    data-name="{{ $child['name'] }}"
                                    data-parent_id="{{ $child['parent_id'] }}"
                                    data-category_id="{{ $child['category_id'] }}"
                                    data-description="{{ $child['description'] }}"
                                    data-slug="{{ $child['slug'] }}"
                                    data-image="{{ $child['image'] }}"
                                    data-size="{{ $child['size'] }}"
                                    data-color="{{ $child['color'] }}"
                                    data-price="{{ number_format($child['price'], 2) }}"
                                    data-stock="{{ $child['stock'] }}"
                                    data-enabled="{{ $child['enabled'] }}"
                            >
                                Edit
                            </button>
                        </td>
                        <td><button class="btn btn-danger buttonDeleteProduct"
                                    data-id="{{ $child['id'] }}"
                                    data-name="{{ $child['name'] }}"
                            >
                                Delete
                            </button></td>
                    </tr>
                    @php
                        $childProductIndex++
                    @endphp
                @endforeach
                @php
                    $parentProductIndex++
                @endphp
            @endforeach
            </tbody>
        </table>
    </div>
</div>




<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add/Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    @csrf
                    <input type="hidden" id="productId" name="productId">

                    <div class="mb-3">
                        <label for="productSku" class="form-label">Sku</label>
                        <input type="text" class="form-control" id="productSku" name="productSku" required>
                    </div>
                    <div class="mb-3">
                        <label for="productName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productImage" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="productImage" name="productImage" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="productDescription" name="productDescription" rows="2" ></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="productSlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="productSlug" name="productSlug" required>
                    </div>

                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Price</label>
                        <input type="text" class="form-control" id="productPrice" name="productPrice" required>
                    </div>

                    <div class="mb-3">
                        <label for="productStock" class="form-label">Stock</label>
                        <input type="text" class="form-control" id="productStock" name="productStock" required>
                    </div>

                    <div class="mb-3">
                        <label for="productSize" class="form-label">Size</label>
                        <input type="text" class="form-control" id="productSize" name="productSize">
                    </div>

                    <div class="mb-3">
                        <label for="productColor" class="form-label">Color</label>
                        <input type="text" class="form-control" id="productColor" name="productColor">
                    </div>

                    <div class="mb-3">
                        <label for="productParent" class="form-label">Product Type</label>
                        <select name="productParent" id="productParent">
                            @foreach($parentProducts as $product)
                                    <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Product Category</label>
                        <select name="productCategory" id="productCategory">
                            @foreach($categories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>
            </div>
        </div>
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
    $(document).ready(function(){
        $(".buttonDeleteProduct").on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var productData = {
                _token: $('input[name="_token"]').val(),
                name: $(this).data('name'),
                id: $(this).data('id'),
            };

            if(confirm("Are you sure to delete \"" + name + "\""))
            {
                //Delete Product
                $.ajax({
                    url: '/api/products/'+id,
                    method: 'DELETE',
                    data: JSON.stringify(productData),
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer {{ $token }}'
                    },
                    success: function(response) {
                        console.log('Product deleted:', response);
                        location.reload();
                    },
                    error: function(error) {
                        alert(error.responseJSON.errors.message);
                        console.error('Error:', error.responseJSON.errors.message);
                    }
                });
            }
        });

        $("#addProductBtn").on('click', function(){
            $('#productModalLabel').text('Add Product');
            $('#productId').val('');
            $('#productSku').val('');
            $('#productName').val('');
            $('#productImage').val('');
            $('#productDescription').val('');
            $('#productSlug').val('');
            $('#productPrice').val('');
            $('#productStock').val('');
            $('#productSize').val('');
            $('#productColor').val('');
            $('#productParent').val('');
            $('#productCategory').val('');
        });

        $(".buttonLoadFormEditProduct").on('click', function(){
            var id = $(this).data('id');
            var sku = $(this).data('sku');
            var name = $(this).data('name');
            var description = $(this).data('description');
            var slug = $(this).data('slug');
            var image = $(this).data('image');
            var price = $(this).data('price');
            var stock = $(this).data('stock');
            var parent_id = $(this).data('parent_id');
            var category_id = $(this).data('category_id');
            var size = $(this).data('size');
            var color = $(this).data('color');

            $.ajax({
                url: '/api/products/find-by-id/' + id,
                method: 'GET',
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer {{ $token }}'
                },
                success: function(response) {
                    console.log('Product found:', response.data);
                },
                error: function(error) {
                    alert(error.responseJSON.errors.message);
                    console.error('Error:', error.responseJSON.errors.message);
                }
            });

            $('#productModalLabel').text('Edit Product');
            $('#productId').val(id);
            $('#productName').val(name);
            $('#productImage').val(image);
            $('#productDescription').val(description);
            $('#productSlug').val(slug);
            $('#productCategory').val(category_id);
            $('#productParent').val(parent_id);
            $('#productSku').val(sku);
            $('#productSize').val(size);
            $('#productColor').val(color);
            $('#productPrice').val(price);
            $('#productStock').val(stock);
        });

        $('#productForm').on('submit', function(event){
            event.preventDefault();
            var productId = $('#productId').val();

            var productData = {
                _token: $('input[name="_token"]').val(),
                name: $('#productName').val(),
                image: $('#productImage').val(),
                description: $('#productDescription').val(),
                slug: $('#productSlug').val(),
                parent_id: $('#productParent').val(),
                sku: $('#productSku').val(),
                category_id: $('#productCategory').val(),
                size: $('#productSize').val(),
                color: $('#productColor').val(),
                price: $('#productPrice').val(),
                stock: $('#productStock').val(),
            };

            if(!productId)
            {
                //Create Product
                $.ajax({
                    url: '/api/products',
                    method: 'POST',
                    data: JSON.stringify(productData),
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer {{ $token }}'
                    },
                    success: function(response) {
                        console.log('Product updated:', response);
                        $('#productModal').modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        alert(error.responseJSON.errors.message);
                        console.error('Error:', error.responseJSON.errors.message);
                    }
                });
            }
            else
            {
                //Update Product
                $.ajax({
                    url: '/api/products/'+productId,
                    method: 'PUT',
                    data: JSON.stringify(productData),
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer {{ $token }}'
                    },
                    success: function(response) {
                        console.log('Product updated successfully:', response);
                        $('#productModal').modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        alert(error.responseJSON.errors.message);
                        console.error('Error:', error.responseJSON.errors.message);
                    }
                });
            }
        });
    });
</script>
@endsection
