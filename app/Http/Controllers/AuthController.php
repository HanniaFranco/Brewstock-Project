<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Sesión iniciada correctamente');
        }

        // Marcar que hubo un intento fallido para mostrar el enlace de reset
        session(['login_failed' => true]);

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Mostrar formulario de solicitud de reseteo de contraseña
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar email de reseteo de contraseña
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Se ha enviado el enlace de reseteo a tu email');
        }

        return back()->withErrors(['email' => 'No encontramos un usuario con ese email']);
    }

    /**
     * Mostrar formulario de reseteo de contraseña
     */
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Procesar reseteo de contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Tu contraseña ha sido reseteada correctamente');
        }

        return back()->withErrors(['email' => [trans($status)]]);
    }
}
