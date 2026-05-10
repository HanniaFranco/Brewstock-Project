@if(!empty($recommendedRecipes) && count($recommendedRecipes) > 0)
<div class="recommendations-section">
    <div class="recommendations-header">
                <h5>Recomendaciones Inteligentes</h5>
        <span class="badge badge-info">{{ count($recommendedRecipes) }}</span>
    </div>
    
    <div class="recommendations-list">
        @foreach($recommendedRecipes as $recommendation)
        <div class="recommendation-card">
            <div class="recommendation-icon">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="recommendation-content">
                <h6 class="recommendation-title">
                    {{ $recommendation['recipe']->product->name ?? 'Receta #' . $recommendation['recipe']->id }}
                </h6>
                <p class="recommendation-reason">
                    <i class="fas fa-info-circle"></i>
                    {{ $recommendation['reason'] }}
                </p>
                <div class="recommendation-meta">
                    <span class="priority-badge priority-{{ $recommendation['score'] > 50 ? 'high' : ($recommendation['score'] > 25 ? 'medium' : 'low') }}">
                        {{ $recommendation['score'] > 50 ? 'Alta Prioridad' : ($recommendation['score'] > 25 ? 'Media Prioridad' : 'Baja Prioridad') }}
                    </span>
                </div>
            </div>
            @php
                $recipeName = $recommendation['recipe']->product->name ?? 'Receta';
                $recipeSlug = \Illuminate\Support\Str::slug($recipeName);
            @endphp
            <a href="{{ url('/inventory/recipes/' . $recipeSlug) }}" class="btn btn-sm btn-primary">
                Ver Receta
            </a>
        </div>
        @endforeach
    </div>
</div>

<style>
.recommendations-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid #dee2e6;
}

.recommendations-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #dee2e6;
}

.recommendations-header i {
    font-size: 24px;
    color: #ffc107;
}

.recommendations-header h5 {
    margin: 0;
    font-weight: 700;
    color: #495057;
}

.recommendations-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.recommendation-card {
    display: flex;
    align-items: center;
    gap: 16px;
    background: white;
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.recommendation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.recommendation-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #8fbc8f 0%, #5a7248 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.recommendation-content {
    flex: 1;
}

.recommendation-title {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #333;
    font-size: 16px;
}

.recommendation-reason {
    margin: 0 0 8px 0;
    font-size: 13px;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 6px;
}

.recommendation-reason i {
    color: #17a2b8;
    font-size: 12px;
}

.priority-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.priority-high {
    background: #f8d7da;
    color: #721c24;
}

.priority-medium {
    background: #fff3cd;
    color: #856404;
}

.priority-low {
    background: #d1ecf1;
    color: #0c5460;
}
</style>
@else
<div class="recommendations-section empty">
    <div class="recommendations-header">
        <h5>Recomendaciones Inteligentes</h5>
    </div>
    <div class="empty-state">
        <i class="fas fa-check-circle"></i>
        <p>No hay recomendaciones pendientes. Todos tus ingredientes están en buen estado.</p>
    </div>
</div>

<style>
.recommendations-section.empty {
    opacity: 0.8;
}

.empty-state {
    text-align: center;
    padding: 32px;
    color: #6c757d;
}

.empty-state i {
    font-size: 48px;
    color: #28a745;
    margin-bottom: 16px;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}
</style>
@endif
