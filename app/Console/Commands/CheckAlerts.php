<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AlertSetting;
use App\Models\Alert;
use App\Models\Ingredient;
use App\Models\Recipe;

class CheckAlerts extends Command
{
    protected $signature = 'alerts:check';
    protected $description = 'Check alert settings and create alerts when thresholds are reached';

    public function handle()
    {
        $this->info('Checking alert settings...');

        $settings = AlertSetting::where('enabled', true)->get();

        foreach ($settings as $s) {
            try {
                if ($s->ingredient_id) {
                    $ing = Ingredient::find($s->ingredient_id);
                    if (! $ing) continue;

                    if ((float) $ing->current_stock <= (float) $s->threshold) {
                        $message = 'Stock bajo: ' . $ing->name . ' (disponible: ' . $ing->current_stock . ' ' . ($ing->unit ?? '') . ')';
                        $this->createAlertIfNotExists($s, 'low_stock', $message);
                    }
                }
            } catch (\Throwable $e) {
                $this->error('Error checking setting '.$s->id.': '.$e->getMessage());
            }
        }

        $this->info('Done.');
        return 0;
    }

    protected function createAlertIfNotExists(AlertSetting $s, $type, $message)
    {
        // Avoid creating duplicates: check last unread similar alert
        $existing = Alert::where('product_id', $s->product_id)
            ->where('ingredient_id', $s->ingredient_id)
            ->where('is_read', false)
            ->where('type', $type)
            ->first();

        if ($existing) return;

        Alert::create([
            'user_id' => $s->user_id ?? null,
            'product_id' => $s->product_id,
            'ingredient_id' => $s->ingredient_id,
            'type' => $type,
            'message' => $message,
            'is_read' => false,
            'created_at' => now(),
        ]);
    }
}
