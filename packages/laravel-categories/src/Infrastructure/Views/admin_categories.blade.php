<!-- resources/views/categories.blade.php -->
@extends('layouts.app')
@section('content')
    <style>
        .category-card {
            margin-bottom: 20px;
        }
        .category-img {
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
<div class="container mt-4">
    <h1 class="text-center">Admin. Categories</h1>

    <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#categoryModal" id="addCategoryBtn">Add new category</button>
    </div>

    <div class="row" id="category-list">
        @foreach($categories as $category)
            <div class="col-md-4 category-card">
                <div class="card">
                    <img src="{{ $category['image'] }}" class="card-img-top category-img" alt="{{ $category['name'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category['name'] }}</h5>
                        <p class="card-text"><strong>Description:</strong> {{ $category['description'] }}</p>
                        <button class="btn btn-primary editCategoryBtn buttonLoadFormEditCategory"  data-bs-toggle="modal" data-bs-target="#categoryModal"
                                data-id="{{ $category['id'] }}"
                                data-name="{{ $category['name'] }}"
                                data-description="{{ $category['description'] }}"
                                data-slug="{{ $category['slug'] }}"
                                data-image="{{ $category['image'] }}"
                                data-enabled="{{ $category['enabled'] }}"
                                data-parent_id="{{ $category['parent_id'] }}"
                                >
                            Edit
                        </button>

                        <button class="btn btn-danger buttonDeleteCategory"
                                data-id="{{ $category['id'] }}"
                                data-name="{{ $category['name'] }}"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Add/Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    @csrf
                    <input type="hidden" id="categoryId" name="categoryId">

                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryImage" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="categoryImage" name="categoryImage">
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="categoryDescription" name="categoryDescription" rows="2" ></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="categorySlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="categorySlug" name="categorySlug" required>
                    </div>

                    <div class="mb-3">
                        <label for="categoryEnabled" class="form-label">Enabled</label>
                        <select name="categoryEnabled" id="categoryEnabled">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="categoryParent" class="form-label">Parent Category</label>
                        <select name="categoryParent" id="categoryParent">
                            <option value="">Parent Category</option>
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
        $(".buttonDeleteCategory").on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var categoryData = {
                _token: $('input[name="_token"]').val(),
                name: $(this).data('name'),
                id: $(this).data('id'),
            };

            if(confirm("Are you sure to delete \"" + name + "\""))
            {
                //Delete Category
                $.ajax({
                    url: '/api/categories/'+id,
                    method: 'DELETE',
                    data: JSON.stringify(categoryData),
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer {{ $token }}'
                    },
                    success: function(response) {
                        console.log('Category deleted:', response);
                        location.reload();
                    },
                    error: function(error) {
                        alert(error.responseJSON.errors.message);
                        console.error('Error:', error.responseJSON.errors.message);
                    }
                });
            }
        });

        $("#addCategoryBtn").on('click', function(){
            $('#categoryModalLabel').text('Add Category');
            $('#categoryId').val('');
            $('#categoryName').val('');
            $('#categoryImage').val('');
            $('#categoryDescription').val('');
            $('#categorySlug').val('');
            $('#categoryEnabled').val('1');
            $('#categoryParent').val('');
        });

        $(".buttonLoadFormEditCategory").on('click', function(){
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            var slug = $(this).data('slug');
            var image = $(this).data('image');
            var enabled = $(this).data('enabled');
            var parent_id = $(this).data('parent_id');

            $.ajax({
                url: '/api/categories/find-by-id/' + id,
                method: 'GET',
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer {{ $token }}'
                },
                success: function(response) {
                    console.log('Category found:', response.data);
                },
                error: function(error) {
                    alert(error.responseJSON.errors.message);
                    console.error('Error:', error.responseJSON.errors.message);
                }
            });

            $('#categoryModalLabel').text('Edit Category');
            $('#categoryId').val(id);
            $('#categoryName').val(name);
            $('#categoryImage').val(image);
            $('#categoryDescription').val(description);
            $('#categorySlug').val(slug);
            $('#categoryEnabled').val(enabled);
            $('#categoryParent').val(parent_id);
        });

        $('#categoryForm').on('submit', function(event){
            event.preventDefault();
            var categoryId = $('#categoryId').val();

            var categoryData = {
                _token: $('input[name="_token"]').val(),
                name: $('#categoryName').val(),
                image: $('#categoryImage').val(),
                description: $('#categoryDescription').val(),
                slug: $('#categorySlug').val(),
                enabled: $('#categoryEnabled').val(),
                parent_id: $('#categoryParent').val()

            };

            if(!categoryId)
            {
                //Create Category
                $.ajax({
                    url: '/api/categories',
                    method: 'POST',
                    data: JSON.stringify(categoryData),
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer {{ $token }}'
                    },
                    success: function(response) {
                        console.log('Category updated:', response);
                         $('#categoryModal').modal('hide');
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
                //Update Category
                $.ajax({
                    url: '/api/categories/'+categoryId,
                    method: 'PUT',
                    data: JSON.stringify(categoryData),
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer {{ $token }}'
                    },
                    success: function(response) {
                        console.log('Category updated successfully:', response);
                        $('#categoryModal').modal('hide');
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
