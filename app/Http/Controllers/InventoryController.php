<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.index');
    }

    public function ingredients()
    {
        return view('inventory.ingredients');
    }

    public function recipes()
    {
        return view('inventory.recipes');
    }
}
