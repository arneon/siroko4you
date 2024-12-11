<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ isset($company_name) ? $company_name : 'Test Company' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<!-- Header -->
<header class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
               <img src="{{ isset($server_fqdn) ? $server_fqdn : 'http://localhost' }}/logo.jpg" alt="Logo" width="100">
            </a>

            <!-- Mobile Buttons -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menú -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/web/products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/web/shopping-cart">Cart</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</header>

<!-- Main Content -->
<main class="container mt-4">
    <div class="row">
        <div class="col">
            @yield('content')
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-dark text-light text-center py-3 mt-auto">
    <div class="container">
        <p>&copy; 2024 Test Company. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
