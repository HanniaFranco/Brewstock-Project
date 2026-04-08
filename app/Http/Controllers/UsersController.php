<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Helpers\LogHelper;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->where('role_id','!=',3)
            ->orderBy('name')
            ->get();

        $totalUsers = $users->count();
        $usersWithRole = $users->whereNotNull('role_id')->count();
        return view('users.index', compact('users', 'totalUsers', 'usersWithRole'));
    }

    public function create()
    {
        $roles = Role::where('id','!=',3)
            ->orderBy('name')
            ->get();

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


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        LogHelper::log(
            'CREATE',
            'User',
            'Nuevo usuario creado: '.$user->name,
            $user->id
        );

        return redirect()
            ->route('users.create')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        $roles = Role::where('id','!=',3)
            ->orderBy('name')
            ->get();

        return view('users.show', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique((new User())->getTable(), 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['nullable', 'integer', Rule::exists((new Role())->getTable(), 'id')],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ]);

        LogHelper::log(
            'UPDATE',
            'User',
            'Usuario actualizado: '.$user->name,
            $user->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'user' => $user
        ]);
    }
}
