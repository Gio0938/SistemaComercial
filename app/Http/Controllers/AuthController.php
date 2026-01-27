<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario; // Importar el nuevo modelo
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Usar el modelo Usuario en lugar de User
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        // Cierra la sesión del usuario
        Auth::logout();

        // Invalida la sesión actual
        $request->session()->invalidate();

        // Regenera el token CSRF
        $request->session()->regenerateToken();

        // Redirige al usuario al login
        return redirect('/login');
    }

    // Método opcional para crear usuarios
    public function createUser()
    {
        // Solo para testing - crear un usuario manualmente
        Usuario::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        return "Usuario creado exitosamente";
    }
}
