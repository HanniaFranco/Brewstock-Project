<?php
namespace App\Http\Controllers;

use App\Models\Log;

class LogsController extends Controller
{
    public function index()
    {
        if(auth()->user()->role_id != 3){
            abort(403);
        }

        $logs = Log::with('user')
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('logs.index', compact('logs'));
    }

    public function clear()
    {
        Log::truncate();

        return redirect()->back()->with('success', 'Logs eliminados.');
    }
}