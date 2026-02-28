<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlertsController extends Controller
{
    public function index()
    {
        return view('alerts.index');
    }

    public function settings()
    {
        return view('alerts.settings');
    }
}
