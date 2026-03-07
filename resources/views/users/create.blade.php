@extends('layouts.app')

@section('title', 'Alta de clientes')
@section('page_title', 'Alta de clientes')

@section('styles')
    <style>
        .form-card {
            background: #f5f5f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 2px solid #8fbc8f;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-card-header {
            padding: 20px 25px;
            border-bottom: 1px solid rgba(143, 188, 143, 0.5);
        }

        .form-card-header h5 {
            margin: 0;
            color: #5a7248;
            font-weight: 600;
            font-size: 16px;
        }

        .form-card-body {
            padding: 25px;
        }

        .btn-primary-custom {
            background-color: #5a7248;
            border-color: #5a7248;
        }

        .btn-primary-custom:hover {
            background-color: #4a5d3a;
            border-color: #4a5d3a;
        }
    </style>
@endsection

@section('content')
    <div class="form-card">
        <div class="form-card-header">
            <h5>Registrar nuevo cliente</h5>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="150">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required maxlength="150">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Rol</label>
                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                        <option value="">Selecciona un rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required minlength="8">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary btn-primary-custom">Guardar cliente</button>
                </div>
            </form>
        </div>
    </div>
@endsection
