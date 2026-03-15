<?php
namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogHelper
{
    public static function log($action, $module, $description = null, $record_id = null)
    {
        Log::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'record_id' => $record_id,
            'ip_address' => Request::ip(),
            'created_at' => now()
        ]);
    }
}