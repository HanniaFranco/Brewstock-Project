<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use Carbon\Carbon;

class RecipeRecommendationService
{
    /**
     * Obtener recetas recomendadas basadas en ingredientes disponibles y fechas de expiración
     * 
     * Algoritmo de priorización:
     * 1. PRIORIDAD ALTA: Ingredientes próximos a caducar (3-7 días) - 100 puntos
     * 2. PRIORIDAD MEDIA: Ingredientes próximos a caducar (8-14 días) - 50 puntos
     * 3. PRIORIDAD BAJA: Ingredientes abundantes (stock alto) - hasta 25 puntos
     * 
     * @param int $limit Número de recomendaciones a retornar
     * @return array
     */
    public function getRecommendations(int $limit = 3): array
    {
        $now = Carbon::now();
        $recommendedRecipes = [];
        $usedRecipeIds = [];
        
        // Obtener TODAS las recetas que tienen al menos un ingrediente configurado
        $recipesWithIngredients = Recipe::whereHas('ingredients', function ($query) {
            $query->whereNotNull('ingredient_id');
        })->with(['product', 'ingredients.ingredient'])->get();
        
        if ($recipesWithIngredients->isEmpty()) {
            return [];
        }
        
        // Array para almacenar recetas candidatas con sus scores
        $recipeCandidates = [];
        
        foreach ($recipesWithIngredients as $recipe) {
            // Verificar que la receta tenga producto asociado
            if (!$recipe->product) {
                continue;
            }
            
            // Verificar que TODOS los ingredientes de la receta estén disponibles
            $canMakeRecipe = true;
            $maxPriorityScore = 0;
            $priorityReason = null;
            $priorityIngredient = null;
            $daysUntilExpiration = null;
            $priorityLevel = 'baja';
            
            foreach ($recipe->ingredients as $recipeIngredient) {
                $ingredient = $recipeIngredient->ingredient;
                
                // Validar que el ingrediente exista y tenga stock suficiente
                if (!$ingredient || $ingredient->current_stock < $recipeIngredient->quantity_required) {
                    $canMakeRecipe = false;
                    break;
                }
                
                // Verificar que no esté expirado
                if ($ingredient->expiration_date && Carbon::parse($ingredient->expiration_date)->isPast()) {
                    $canMakeRecipe = false;
                    break;
                }
                
                // Calcular prioridad del ingrediente
                $ingredientScore = 0;
                $ingredientDays = null;
                $ingredientPriority = 'baja';
                
                if ($ingredient->expiration_date) {
                    $expirationDate = Carbon::parse($ingredient->expiration_date);
                    $ingredientDays = $now->diffInDays($expirationDate, false);
                    
                    if ($ingredientDays < 0) {
                        $canMakeRecipe = false;
                        break;
                    } elseif ($ingredientDays <= 3) {
                        $ingredientScore = 160 - ($ingredientDays * 20); // 100-160
                        $ingredientPriority = 'urgente';
                    } elseif ($ingredientDays <= 7) {
                        $ingredientScore = 95 - (($ingredientDays - 4) * 5); // 80-95
                        $ingredientPriority = 'alta';
                    } elseif ($ingredientDays <= 14) {
                        $ingredientScore = 62 - (($ingredientDays - 8) * 2); // 50-62
                        $ingredientPriority = 'media';
                    } else {
                        // Stock abundante (más de 14 días para expirar)
                        $ingredientScore = min($ingredient->current_stock / 5, 25);
                        if ($ingredient->current_stock >= ($ingredient->minimum_stock * 2)) {
                            $ingredientScore += 15;
                        }
                        $ingredientPriority = 'baja';
                    }
                } else {
                    // Sin fecha de expiración - prioridad por stock
                    $ingredientScore = min($ingredient->current_stock / 5, 25);
                    if ($ingredient->current_stock >= ($ingredient->minimum_stock * 2)) {
                        $ingredientScore += 15;
                    }
                }
                
                // Tomar la mayor prioridad entre todos los ingredientes de la receta
                $priorityOrder = ['urgente' => 4, 'alta' => 3, 'media' => 2, 'baja' => 1];
                $currentPriorityValue = $priorityOrder[$priorityLevel] ?? 0;
                $newPriorityValue = $priorityOrder[$ingredientPriority] ?? 0;
                
                if ($newPriorityValue > $currentPriorityValue || 
                    ($newPriorityValue === $currentPriorityValue && $ingredientScore > $maxPriorityScore)) {
                    $maxPriorityScore = $ingredientScore;
                    $priorityIngredient = $ingredient;
                    $daysUntilExpiration = $ingredientDays;
                    $priorityLevel = $ingredientPriority;
                }
            }
            
            if ($canMakeRecipe && $priorityIngredient) {
                $recipeCandidates[] = [
                    'recipe' => $recipe,
                    'score' => $maxPriorityScore,
                    'priority' => $priorityLevel,
                    'priority_ingredient' => $priorityIngredient,
                    'days_until_expiration' => $daysUntilExpiration,
                ];
            }
        }
        
        // Ordenar por prioridad y score
        $priorityOrder = ['urgente' => 4, 'alta' => 3, 'media' => 2, 'baja' => 1];
        usort($recipeCandidates, function ($a, $b) use ($priorityOrder) {
            $priorityA = $priorityOrder[$a['priority']] ?? 0;
            $priorityB = $priorityOrder[$b['priority']] ?? 0;
            
            if ($priorityA !== $priorityB) {
                return $priorityB - $priorityA; // Mayor prioridad primero
            }
            
            return $b['score'] - $a['score']; // Mayor score primero
        });
        
        // Tomar las primeras $limit recetas
        $result = [];
        foreach ($recipeCandidates as $candidate) {
            if (count($result) >= $limit) {
                break;
            }
            
            $result[] = [
                'recipe' => $candidate['recipe'],
                'priority_ingredient' => $candidate['priority_ingredient'],
                'score' => $candidate['score'],
                'priority' => $candidate['priority'],
                'days_until_expiration' => $candidate['days_until_expiration'],
                'reason' => $this->generateReason([
                    'ingredient' => $candidate['priority_ingredient'],
                    'days_until_expiration' => $candidate['days_until_expiration'],
                    'priority' => $candidate['priority'],
                ])
            ];
        }
        
        return $result;
    }

    /**
     * Verificar si se puede preparar una receta con los ingredientes actuales
     */
    private function canMakeRecipe($recipe): bool
    {
        $recipeIngredients = $recipe->ingredients()->with('ingredient')->get();
        
        foreach ($recipeIngredients as $recipeIngredient) {
            $ingredient = $recipeIngredient->ingredient;
            
            if (!$ingredient || $ingredient->current_stock < $recipeIngredient->quantity_required) {
                return false;
            }
            
            // Verificar que no esté expirado
            if ($ingredient->expiration_date && Carbon::parse($ingredient->expiration_date)->isPast()) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Generar razón de la recomendación basada en la prioridad
     */
    private function generateReason(array $ingredientData): string
    {
        $ingredient = $ingredientData['ingredient'];
        $days = $ingredientData['days_until_expiration'];
        $priority = $ingredientData['priority'];

        switch ($priority) {
            case 'urgente':
                if ($days === 0) {
                    return "¡{$ingredient->name} vence HOY! Prepara esta receta urgentemente.";
                } elseif ($days === 1) {
                    return "¡{$ingredient->name} vence MAÑANA! Aprovecha este ingrediente.";
                } else {
                    return "¡{$ingredient->name} caduca en {$days} días! Úsalo antes de que se pierda.";
                }
            
            case 'alta':
                return "{$ingredient->name} caduca esta semana ({$days} días). Ideal para usar ahora.";
            
            case 'media':
                return "{$ingredient->name} caduca pronto ({$days} días). Planifica su uso.";
            
            case 'baja':
            default:
                $stockLevel = $ingredient->current_stock >= ($ingredient->minimum_stock * 3) ? 'abundante' : 'bueno';
                return "Tienes {$stockLevel} stock de {$ingredient->name}. Aprovecha para preparar esta receta.";
        }
    }
}
