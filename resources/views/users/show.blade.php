@extends('layouts.app')

@section('title', $user->name)
@section('page_title', 'Usuarios')

@section('styles')
    <style>
        .user-profile-container {
            background: #f5f5f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
            border: 2px solid #8fbc8f;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .user-avatar {
            width: 120px;
            height: 120px;
            background: #8fbc8f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            border: 3px solid #5a7248;
        }

        .user-avatar i {
            font-size: 50px;
            color: white;
        }

        .user-info {
            width: 100%;
            max-width: 400px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            color: #5a7248;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #8fbc8f;
            border-radius: 8px;
            background: white;
            color: #2f3a28;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #5a7248;
            box-shadow: 0 0 0 3px rgba(90, 114, 72, 0.1);
        }

        .form-control:disabled {
            background: #f8f9fa;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .btn-primary {
            background: #5a7248;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            align-self: flex-end;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background: #4a5d3a;
        }

        .btn-primary:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #5a7248;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background: #4a5d3a;
        }

        .page-header {
            position: relative;
            margin-bottom: 30px;
        }

        .page-title {
            text-align: center;
            color: #5a7248;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .edit-mode .form-control {
            background: white;
            cursor: text;
        }

        .edit-mode .form-control:focus {
            border-color: #5a7248;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <button class="back-button" onclick="window.location.href='/users'">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h1 class="page-title">Usuarios</h1>
    </div>

    <div class="user-profile-container">
        <!-- User Avatar -->
        <div class="user-avatar">
            <i class="fas fa-user"></i>
        </div>

        <!-- User Information Form -->
        <div class="user-info">
            <form id="userForm">
                <!-- Name -->
                <div class="form-group">
                    <label class="form-label" for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" {{ (int)(auth()->user()->role_id ?? 0) !== 1 ? 'disabled' : '' }}>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" {{ (int)(auth()->user()->role_id ?? 0) !== 1 ? 'disabled' : '' }}>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label class="form-label" for="phone">Número de teléfono</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? '' }}" {{ (int)(auth()->user()->role_id ?? 0) !== 1 ? 'disabled' : '' }}>
                </div>

                <!-- Role (solo para admin) -->
                @if((int)(auth()->user()->role_id ?? 0) === 1)
                    <div class="form-group">
                        <label class="form-label" for="role_id">Rol</label>
                        <select class="form-control" id="role_id" name="role_id">
                            <option value="">Seleccionar rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Form Actions -->
                @if((int)(auth()->user()->role_id ?? 0) === 1)
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="window.location.href='/users'">Cancelar</button>
                        <button type="submit" class="btn-primary">Editar</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Solo permitir edición si es admin
        @if((int)(auth()->user()->role_id ?? 0) === 1)
            document.getElementById('userForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const userData = {
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    role_id: formData.get('role_id'),
                    _method: 'PUT'
                };
                
                fetch('/users/{{ $user->id }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(userData)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Usuario actualizado correctamente');
                        window.location.reload();
                    } else {
                        alert(result.message || 'Error al actualizar el usuario');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión al actualizar el usuario');
                });
            });
        @endif
    </script>
@endsection
