@extends('layouts.app')

@section('title', 'Usuarios')
@section('page_title', 'Usuarios')

@section('styles')
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #f5f5f0;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 2px solid #8fbc8f;
            text-align: center;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .stat-card h3 {
            color: #5a7248;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            margin-top: 15px;
        }

        .stat-card .value {
            color: #4a5d3a;
            font-size: 34px;
            font-weight: 700;
            line-height: 1;
        }

        .content-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: #f5f5f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 0;
            border: 2px solid #8fbc8f;
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: none;
            background-color: transparent;
            text-align: center;
        }

        .card-header h5 {
            margin: 0;
            color: #5a7248;
            font-weight: 600;
            font-size: 16px;
        }

        .card-body {
            padding: 20px 25px;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th,
        .users-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e6ecdf;
            text-align: left;
            font-size: 14px;
        }

        .users-table th {
            color: #5a7248;
            font-weight: 600;
        }

        .users-table td {
            color: #2f3a28;
        }

        .empty-users {
            text-align: center;
            color: #6c757d;
            padding: 20px 0;
        }

        .bottom-card {
            background: #f5f5f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 30px;
            min-height: 150px;
            border: 2px solid #8fbc8f;
        }
    </style>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <h3>Total Usuarios</h3>
            <div class="value">{{ $totalUsers }}</div>
        </div>
        <div class="stat-card">
            <h3>Usuarios con Rol</h3>
            <div class="value">{{ $usersWithRole }}</div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="content-row">
        <div class="card">
            <div class="card-header">
                <h5>Lista de Usuarios</h5>
            </div>
            <div class="card-body">
                @if((int) (auth()->user()->role_id ?? 0) === 1)
                    <div class="mb-3 text-end">
                        <a href="{{ route('users.create') }}" class="btn btn-success">Nuevo usuario</a>
                    </div>
                @endif

                @if($users->isEmpty())
                    <p class="empty-users">No hay usuarios registrados.</p>
                @else
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role?->name ?? 'Sin rol' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Bottom Card -->
    <div class="bottom-card">
    </div>
@endsection
