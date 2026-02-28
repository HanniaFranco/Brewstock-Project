<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Brewstock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .forgot-container {
            display: flex;
            width: 100vw;
            height: 100vh;
            box-shadow: none;
            border-radius: 0;
            box-sizing: border-box;
        }

        .forgot-content {
            display: flex;
            width: 100%;
            height: 100%;
            background-color: white;
        }

        .forgot-form-section {
            flex: 0 0 50%;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: white;
        }

        .forgot-pattern-section {
            flex: 0 0 50%;
            background-image: url('{{ asset('assets/loginimage.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #fcfaf5;
            display: block;
        }

        @media (max-width: 768px) {
            .forgot-pattern-section {
                display: none;
            }

            .forgot-form-section {
                width: 100%;
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-image {
            max-width: 200px;
            height: auto;
            margin-bottom: 10px;
        }

        .logo h1 {
            color: #5a7248;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .logo p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 60%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            margin-left: auto;
            margin-right: auto;
        }

        .form-group input:focus {
            outline: none;
            border-color: #5a7248;
        }

        .submit-btn {
            width: 60%;
            padding: 12px;
            background-color: #5a7248;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 20px auto 0;
            display: block;
        }

        .submit-btn:hover {
            background-color: #4a5d3a;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #5a7248;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #4a5d3a;
            text-decoration: underline;
        }

        .alert {
            margin-bottom: 20px;
            text-align: center;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-content">
            <div class="forgot-form-section">
                <div class="logo">
                    <img src="{{ url('/logo.png') }}" alt="brewstock" class="logo-image">
                    <p style="color: #666; font-size: 16px; margin-top: -10px;">Admin Portal</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                               placeholder="Ingresa tu correo electrónico" autofocus>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">
                        Enviar Enlace de Recuperación
                    </button>
                </form>

                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i> Volver al Login
                    </a>
                </div>
            </div>

            <div class="forgot-pattern-section"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
