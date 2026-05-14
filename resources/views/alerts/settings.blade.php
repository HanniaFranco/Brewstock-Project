@extends('layouts.app')

@section('title', 'Configuración de Alertas')
@section('page_title', 'Configuración de Alertas')

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
            <h3>Reglas Activas</h3>
        </div>
        <div class="stat-card">
            <h3>Notificaciones</h3>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="content-row">
        <div class="card">
            <div class="card-header">
                <h5>Configuración de Alertas</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('alerts.settings.store') }}">
                    @csrf

                    <h6>Ingredientes</h6>
                    <div class="mb-3">
                        @foreach($groupedIngredients as $category => $items)
                            <div class="category-separator" style="margin-top:18px; padding:10px; background:#e9f5ea; border-radius:6px; border:1px solid #d6ead6;">
                                <strong>{{ $category }}</strong>
                            </div>

                            @foreach($items as $ingredient)
                                <?php $key = 'i_'.$ingredient->id; $s = $settings[$key] ?? null; ?>
                                <div class="form-group" style="margin-top:8px;">
                                    <label>{{ $ingredient->name }} ({{ $ingredient->unit }})</label>
                                    <input type="hidden" name="ingredient[{{ $ingredient->id }}][id]" value="{{ $ingredient->id }}">
                                    <input type="number" step="0.01" min="0" name="ingredient[{{ $ingredient->id }}][threshold]" value="{{ $s->threshold ?? '' }}" class="form-control" placeholder="Cantidad objetivo para alerta (ej: 0.50)">
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    <button class="btn btn-primary">Guardar configuración</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bottom Card -->
    <div class="bottom-card">
    </div>
@endsection
