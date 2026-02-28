@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: white;
            padding: 25px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border-top: 3px solid #5a7248;
        }

        .stat-card h3 {
            color: #888;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            color: #5a7248;
            margin-bottom: 5px;
            line-height: 1;
        }

        .stat-card .subtitle {
            font-size: 12px;
            color: #aaa;
            margin-top: 8px;
        }

        .content-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        @media (max-width: 992px) {
            .content-row {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid #e8e8e8;
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            color: #5a7248;
            font-size: 16px;
        }

        .card-header h5 {
            margin: 0;
            color: #333;
            font-weight: 600;
            font-size: 15px;
        }

        .card-body {
            padding: 40px 20px;
            min-height: 200px;
        }

        .no-data {
            text-align: center;
            color: #999;
        }

        .no-data i {
            font-size: 50px;
            color: #e8e8e8;
            margin-bottom: 15px;
            display: block;
        }

        .no-data i.check-icon {
            color: #7a8f68;
        }

        .no-data p {
            font-size: 14px;
            margin: 0;
            color: #999;
        }

        .latest-sales-table {
            width: 100%;
            border-collapse: collapse;
        }

        .latest-sales-table th,
        .latest-sales-table td {
            padding: 12px 20px;
            text-align: left;
            font-size: 13px;
        }

        .latest-sales-table th {
            background-color: #f8f9fa;
            color: #666;
            font-weight: 600;
            border-bottom: 1px solid #eee;
        }

        .latest-sales-table td {
            border-bottom: 1px solid #f5f5f5;
            color: #555;
        }

        .latest-sales-table tr:last-child td {
            border-bottom: none;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <h3>Productos más vendidos</h3>
            <div class="value">0</div>
            <div class="subtitle">en el último período</div>
        </div>

        <div class="stat-card">
            <h3>Ventas de hoy</h3>
            <div class="value">$0.00</div>
            <div class="subtitle">ingresos totales</div>
        </div>

        <div class="stat-card">
            <h3>Alertas pendientes</h3>
            <div class="value">0</div>
            <div class="subtitle">por revisar</div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="content-row">
        <!-- Productos más vendidos -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line"></i>
                <h5>Productos más vendidos</h5>
            </div>
            <div class="card-body">
                <div class="no-data">
                    <i class="fas fa-shopping-cart"></i>
                    <p>No hay ventas registradas aún</p>
                </div>
            </div>
        </div>

        <!-- Alertas Recientes -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bell"></i>
                <h5>Alertas Recientes</h5>
            </div>
            <div class="card-body">
                <div class="no-data">
                    <i class="fas fa-check-circle check-icon"></i>
                    <p>No hay alertas pendientes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimas Ventas -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-history"></i>
            <h5>Últimas Ventas</h5>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="latest-sales-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Productos</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                No hay ventas registradas
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
