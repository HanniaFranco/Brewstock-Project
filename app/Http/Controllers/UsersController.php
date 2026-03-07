<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->orderBy('name')
            ->get();

        $totalUsers = $users->count();
        $usersWithRole = $users->whereNotNull('role_id')->count();

        return view('users.index', compact('users', 'totalUsers', 'usersWithRole'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique((new User())->getTable(), 'email'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', Rule::exists((new Role())->getTable(), 'id')],
        ]);

        User::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
            'role_id' => (int) $request->input('role_id'),
        ]);

        return redirect()
            ->route('users.create')
            ->with('success', 'Cliente registrado correctamente.');
    }
}
