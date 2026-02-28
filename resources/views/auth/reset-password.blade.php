<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetear Contraseña - Brewstock</title>
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

        .reset-container {
            display: flex;
            width: 100vw;
            height: 100vh;
            box-shadow: none;
            border-radius: 0;
            box-sizing: border-box;
        }

        .reset-content {
            display: flex;
            width: 100%;
            height: 100%;
            background-color: white;
        }

        .reset-form-section {
            flex: 0 0 50%;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: white;
        }

        .reset-pattern-section {
            flex: 0 0 50%;
            background-image: url('{{ asset('assets/loginimage.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #fcfaf5;
            display: block;
        }

        @media (max-width: 768px) {
            .reset-pattern-section {
                display: none;
            }

            .reset-form-section {
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

        .password-requirements {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
            color: #666;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-content">
            <div class="reset-form-section">
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

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') ?? request()->email }}" required 
                               placeholder="Ingresa tu correo electrónico" autofocus>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Ingresa tu nueva contraseña">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required 
                               placeholder="Confirma tu nueva contraseña">
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="password-requirements">
                        La contraseña debe tener al menos 8 caracteres
                    </div>

                    <button type="submit" class="submit-btn">
                        Resetear Contraseña
                    </button>
                </form>

                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i> Volver al Login
                    </a>
                </div>
            </div>

            <div class="reset-pattern-section"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
