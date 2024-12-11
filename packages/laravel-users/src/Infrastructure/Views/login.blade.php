<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Iniciar Sesión</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form id="loginForm">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@dominio.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            // Limpiar mensajes de error
            $('#emailError').text('');
            $('#passwordError').text('');

            $.ajax({
                url: "{{ route('login') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response)
                    window.location.href = "{{ $login_redirect_endpoint }}";
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    alert(errors.message);
                    console.log(errors)
                    if (errors.message) {
                        $('#emailError').text(errors.message);
                        $('#email').addClass('is-invalid');
                    }
                }
            });
        });
    });
</script>

</body>
</html>
