@extends('layouts.app')

@section('page_title', 'Logs del Sistema')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <strong>Logs del Sistema</strong>

        <form action="{{ route('logs.clear') }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Borrar Logs
            </button>
        </form>
    </div>

    <div class="card-body">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Módulo</th>
                    <th>Descripción</th>
                    <th>IP</th>
                    <th>Fecha</th>
                </tr>
            </thead>

            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->user->name ?? 'Sistema' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->module }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>

        {{ $logs->links() }}

    </div>
</div>

@endsection