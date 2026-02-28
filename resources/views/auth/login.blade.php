<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Brewstock</title>
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

        .login-container {
            display: flex;
            width: 100vw;
            height: 100vh;
            box-shadow: none;
            border-radius: 0;
            /* Eliminado: background-color: #e0e0e0; */
            /* Eliminado: padding: 20px; */
            box-sizing: border-box;
        }

        .login-content {
            display: flex;
            width: 100%;
            height: 100%;
            background-color: white; /* Fondo blanco para el contenido principal */
        }

        .login-form-section {
            flex: 0 0 50%; /* Exactamente la mitad del ancho */
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: white;
        }

        .login-pattern-section {
            flex: 0 0 50%; /* Exactamente la mitad del ancho */
            background-image: url('{{ asset('assets/loginimage.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #fcfaf5; /* Color de fondo crema para la imagen */
            display: block;
        }

        @media (max-width: 768px) {
            .login-pattern-section {
                display: none;
            }

            .login-form-section {
                width: 100%;
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 50px;
        }

        .logo-image {
            max-width: 250px;
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

        .form-group.email-input {
            position: relative;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
        }

        .form-group.email-input label {
            text-align: left;
        }

        .form-group.email-input input {
            width: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #666;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
        }

        .form-group input {
            width: 60%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
            background-color: #f8f8f8;
            text-align: left;
        }

        .form-group.password-input {
            position: relative;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
        }

        .form-group.password-input label {
            text-align: left;
        }

        .form-group.password-input input {
            width: 100%;
            padding-right: 40px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: calc(50% + 14px); /* Bajado 14px */
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 16px;
            background: none;
            border: none;
            outline: none;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #333;
        }

        .form-group input:focus {
            outline: none;
            border-color: #8b9d6f;
            box-shadow: 0 0 0 3px rgba(139, 157, 111, 0.1);
        }

        .login-btn {
            width: 60%;
            padding: 12px 15px;
            background-color: #5a7248;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .login-btn:hover {
            background-color: #4a5d3a;
        }

        .login-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        .alert {
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 20px;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 0;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .remember-me label {
            margin-bottom: 0;
            cursor: pointer;
            font-size: 13px;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 15px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .loading.show {
            display: flex;
        }

        .spinner-container {
            position: relative;
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }

        .spinner {
            border: 4px solid rgba(107, 134, 89, 0.2);
            border-radius: 50%;
            border-top: 4px solid #5a7248;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            position: absolute;
        }

        .spinner-inner {
            border: 4px solid rgba(107, 134, 89, 0.3);
            border-radius: 50%;
            border-top: 4px solid #7a8f68;
            width: 40px;
            height: 40px;
            animation: spin 1.5s linear infinite reverse;
            position: absolute;
            top: 10px;
            left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 14px;
            color: #5a7248;
            font-weight: 500;
        }

        .loading-user {
            font-size: 16px;
            color: #4a5d3a;
            font-weight: 700;
            margin-top: 5px;
        }

        .forgot-password-link {
            color: #5a7248;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .forgot-password-link:hover {
            color: #4a5d3a;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-content">
            <div class="login-form-section">
                <div class="logo">
                    <img src="{{ url('/logo.png') }}" alt="brewstock" class="logo-image">
                    <p style="color: #666; font-size: 16px; margin-top: -10px;">Admin Portal</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error</strong> Las credenciales no son correctas.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group email-input">
                        <label for="email">Usuario</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                            placeholder="Ingresa tu correo" autofocus>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group password-input">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required 
                            placeholder="Ingresa tu contraseña">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" value="1">
                        <label for="remember">Recuérdame</label>
                    </div>

                    <button type="submit" class="login-btn" id="submitBtn">Iniciar Sesión</button>
                    
                    @if(session('login_failed'))
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="forgot-password-link">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    @endif
                    
                    <div class="loading" id="loadingScreen">
                        <div class="spinner-container">
                            <div class="spinner"></div>
                            <div class="spinner-inner"></div>
                        </div>
                        <div class="loading-text">Iniciando Sesión</div>
                        <div class="loading-user" id="loadingUserName">ADMIN</div>
                    </div>
                </form>
            </div>

            <div class="login-pattern-section"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = document.getElementById('email');
            const userName = emailInput.value.split('@')[0] || 'ADMIN';
            document.getElementById('loadingUserName').textContent = userName.toUpperCase();
            
            document.getElementById('submitBtn').style.display = 'none';
            document.getElementById('loadingScreen').classList.add('show');
            
            setTimeout(() => {
                this.submit();
            }, 1500);
        });
    </script>
</body>
</html>
