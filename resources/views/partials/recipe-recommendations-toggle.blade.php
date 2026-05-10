<div class="recommendations-toggle-container">
    <!-- Botón para mostrar/ocultar recomendaciones -->
    <button type="button" class="btn btn-recommendations" id="toggleRecommendations" onclick="toggleRecommendations()">
        <span class="btn-text">Ver Recomendaciones Inteligentes</span>
        @if(!empty($recommendedRecipes) && count($recommendedRecipes) > 0)
            <span class="badge-count">{{ count($recommendedRecipes) }} recetas recomendadas</span>
        @endif
        <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
    </button>

    <!-- Sección de recomendaciones (inicialmente oculta) -->
    <div class="recommendations-content" id="recommendationsContent" style="display: none;">
        @if(!empty($recommendedRecipes) && count($recommendedRecipes) > 0)
            <div class="recommendations-list">
                @foreach($recommendedRecipes as $recommendation)
                <div class="recommendation-card priority-{{ $recommendation['priority'] ?? 'baja' }}">
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
                            @php
                                $priorityLabels = [
                                    'urgente' => ['label' => '⚠️ Urgente: Caduca pronto', 'class' => 'urgente'],
                                    'alta' => ['label' => '🔴 Prioridad Alta', 'class' => 'alta'],
                                    'media' => ['label' => '🟡 Prioridad Media', 'class' => 'media'],
                                    'baja' => ['label' => '🟢 Stock Abundante', 'class' => 'baja']
                                ];
                                $priority = $recommendation['priority'] ?? 'baja';
                            @endphp
                            <span class="priority-badge priority-{{ $priorityLabels[$priority]['class'] }}">
                                {{ $priorityLabels[$priority]['label'] }}
                            </span>
                            @if(isset($recommendation['days_until_expiration']) && $recommendation['days_until_expiration'] !== null)
                                <span class="expiration-badge">
                                    @if($recommendation['days_until_expiration'] <= 3)
                                        ⏰ {{ $recommendation['days_until_expiration'] }} días
                                    @elseif($recommendation['days_until_expiration'] <= 7)
                                        📅 {{ $recommendation['days_until_expiration'] }} días
                                    @endif
                                </span>
                            @endif
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
        @else
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <p>No hay recomendaciones pendientes. Todos tus ingredientes están en buen estado.</p>
            </div>
        @endif
    </div>
</div>

<style>
.recommendations-toggle-container {
    margin-bottom: 24px;
}

.btn-recommendations {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    width: 100%;
    padding: 14px 18px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 15px;
    font-weight: 600;
    color: #495057;
}

.btn-recommendations:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-color: #adb5bd;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-text {
    flex: 1;
    text-align: left;
    font-weight: 600;
}

.badge-count {
    background: #17a2b8;
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
}

.toggle-icon {
    font-size: 14px;
    color: #6c757d;
    transition: transform 0.3s ease;
}

.toggle-icon.rotated {
    transform: rotate(180deg);
}

.recommendations-content {
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
    padding: 20px;
    border: 2px solid #dee2e6;
    border-top: none;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
    border-left: 4px solid #dee2e6;
}

.recommendation-card.priority-urgente {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, #fff 0%, #f8d7da 100%);
}

.recommendation-card.priority-alta {
    border-left-color: #fd7e14;
    background: linear-gradient(135deg, #fff 0%, #ffe5d4 100%);
}

.recommendation-card.priority-media {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, #fff 0%, #fff3cd 100%);
}

.recommendation-card.priority-baja {
    border-left-color: #28a745;
}

.recommendation-card:hover {
    transform: translateX(4px);
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

.recommendation-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
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

.priority-urgente {
    background: #f8d7da;
    color: #721c24;
}

.priority-alta {
    background: #ffe5d4;
    color: #7c2d12;
}

.priority-media {
    background: #fff3cd;
    color: #856404;
}

.priority-baja {
    background: #d4edda;
    color: #155724;
}

.expiration-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    background: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
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

<script>
function toggleRecommendations() {
    const content = document.getElementById('recommendationsContent');
    const icon = document.getElementById('toggleIcon');
    const button = document.getElementById('toggleRecommendations');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.add('rotated');
        button.style.borderRadius = '12px 12px 0 0';
    } else {
        content.style.display = 'none';
        icon.classList.remove('rotated');
        button.style.borderRadius = '12px';
    }
}
</script>
