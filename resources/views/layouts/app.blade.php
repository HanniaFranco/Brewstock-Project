<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Brewstock') - Brewstock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7f4;
        }

        .container-fluid {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: transparent;
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0;
            background-color: white;
        }

        .sidebar-body {
            background-color: #4a5d3a;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .sidebar-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            color: white;
            text-transform: lowercase;
        }

        .sidebar-logo {
            max-width: 200px;
            height: auto;
            display: block;
        }

        .sidebar-menu {
            list-style: none;
            padding-top: 20px;
            flex: 1;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: #8fbc8f;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }

        .sidebar-menu .submenu {
            list-style: none;
            padding-left: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease-out;
            margin: 0;
        }

        .sidebar-menu .submenu li {
            list-style: none;
        }

        .sidebar-menu .submenu a {
            padding: 10px 20px 10px 55px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: underline;
            text-decoration-color: rgba(255, 255, 255, 0.3);
            font-size: 14px;
            display: block;
            border-left: none;
        }

        .sidebar-menu li.active > .submenu {
            max-height: 500px;
        }

        .sidebar-footer {
            position: static;
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background-color: #4a5d3a;
            margin-top: auto;
        }

        .user-menu-container {
            position: relative;
        }

        .user-menu-toggle {
            display: flex;
            align-items: center;
            padding: 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .user-menu-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-menu-toggle i:first-child {
            margin-right: 10px;
        }

        .user-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease-in-out;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .user-dropdown.show {
            max-height: 100px;
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            padding: 15px 20px 15px 55px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .user-dropdown a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .user-dropdown i {
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar */
        .topbar {
            background-color: transparent;
            padding: 15px 30px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: none;
        }

        .topbar-title {
            color: #333;
            font-weight: 600;
            font-size: 18px;
            display: none;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .topbar-user i {
            margin-right: 5px;
        }

        /* Top Header */
        .top-header {
            background-color: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 100;
            height: 88px; /* Altura del header aumentada para alinear con el logo */
        }

        .page-title {
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .header-user i {
            font-size: 18px;
            color: #666;
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 20px 30px 30px 30px;
            overflow-y: auto;
            background-color: #f8f9fa;
            margin-top: 88px; /* Compensar la altura del header fijo */
        }

        /* Alerts */
        .alert-dismissible {
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                margin-left: -250px;
                transition: margin-left 0.3s;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                position: relative;
            }

            .toggle-sidebar {
                background: none;
                border: none;
                color: #333;
                font-size: 20px;
                cursor: pointer;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                @if(request()->routeIs('login') || request()->routeIs('dashboard'))
                    <img src="{{ url('/logo.png') }}" alt="brewstock" class="sidebar-logo">
                @else
                    <a href="{{ route('dashboard') }}" style="text-decoration: none; display: block;">
                        <img src="{{ url('/logo.png') }}" alt="brewstock" class="sidebar-logo" style="cursor: pointer;">
                    </a>
                @endif
            </div>

            <div class="sidebar-body">
            <ul class="sidebar-menu">
                <li>
                    <a href="javascript:void(0)" data-toggle="submenu" class="{{ request()->routeIs('products*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Productos</span>
                        <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 12px;"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('products.index') }}">Productos</a></li>
                        <li><a href="{{ route('products.categories') }}">Categorías</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0)" data-toggle="submenu" class="{{ request()->routeIs('inventory*') ? 'active' : '' }}">
                        <img src="{{ asset('assets/create-sharp.png') }}" alt="Inventario" style="width: 20px; height: 20px; margin-right: 15px; vertical-align: middle;">
                        <span>Inventario</span>
                        <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 12px;"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('inventory.index') }}">Inventario</a></li>
                        <li><a href="{{ route('inventory.ingredients') }}">Ingredientes</a></li>
                        <li><a href="{{ route('inventory.recipes') }}">Recetas</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users*') ? 'active' : '' }}">
                        <i class="fas fa-user-friends"></i>
                        <span>Usuarios</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Ventas</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0)" data-toggle="submenu" class="{{ request()->routeIs('alerts*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Alertas</span>
                        <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 12px;"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('alerts.index') }}">Alertas</a></li>
                        <li><a href="{{ route('alerts.settings') }}">Configuración</a></li>
                    </ul>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="user-menu-container" id="userMenuContainer">
                    <a href="javascript:void(0)" class="user-menu-toggle" onclick="toggleUserMenu()">
                        <i class="fas fa-user"></i>
                        <div style="flex: 1;">
                            <div style="font-weight: 500;">{{ Auth::user()->name ?? 'Admin User' }}</div>
                            <div style="font-size: 12px; color: rgba(255, 255, 255, 0.6);">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                        <i class="fas fa-chevron-right" style="font-size: 12px;"></i>
                    </a>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <div class="top-header">
                <span class="page-title">@yield('page_title', '')</span>
                <div class="header-user">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name ?? 'Admin User' }}</span>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Por favor, revisa los campos.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // User menu toggle
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Submenu toggle
        document.querySelectorAll('[data-toggle="submenu"]').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.classList.toggle('active');
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
