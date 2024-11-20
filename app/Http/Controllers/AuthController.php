<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        // Validar los datos ingresados en el formulario
        $credentials = $request->validate([
            'rut' => 'required|exists:users,rut', // Verifica que el RUT exista en la base de datos
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt(['rut' => $credentials['rut'], 'password' => $credentials['password']])) {
            $request->session()->regenerate(); 
            return redirect()->intended('/'); 
        }

        // En caso de error, regresar al formulario con un mensaje
        return back()->withErrors([
            'rut' => 'Las credenciales no son correctas.',
        ])->onlyInput('rut');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión actual

        $request->session()->invalidate(); 
        $request->session()->regenerateToken();

        return redirect('/login'); // Redirige al formulario de login
    }

    // Mostrar el formulario de registro
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'rut' => 'required|regex:/^\d{7,8}$/',
            'password' => 'required|string|confirmed',
            'is_admin' => 'required|boolean',
        ]);

        // Verificar si ya existe un usuario con el mismo RUT pero está inactivo
        $existingUser = User::where('rut', $validatedData['rut'])->where('activo', 0)->first();

        if ($existingUser) {
            // Activar usuario existente
            $existingUser->update([
                'name' => $validatedData['name'],
                'password' => Hash::make($validatedData['password']), // Actualizar la contraseña
                'is_admin' => $validatedData['is_admin'] ?? false,
                'activo' => 1, 
            ]);
            
            $message = 'Usuario existente activado exitosamente.';

        }else{
            // Crear el usuario
            User::create([
                'name' => $validatedData['name'],
                'rut' => $validatedData['rut'],
                'password' => Hash::make($validatedData['password']), // Cifrar la contraseña
                'is_admin' => $validatedData['is_admin'] ?? false,
                'activo' => 1,
            ]);

            $message = 'Usuario registrado exitosamente.';

        }




        // Redirigir
        return view('success', [
            'message' => $message,
            'url' => route('welcome'),
        ]);
    }

    public function usersList()
    {
        // todos los usuarios 
        $users = User::where('activo', 1)->paginate(15);

        // Retorna la vista con los usuarios
        return view('users.lista', compact('users'));
    }

    public function update(Request $request, $id)
    {
        // Valida los datos entrantes
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'is_admin' => 'required|boolean',
            'password' => 'nullable|string', 
        ]);
    
        // Actualiza los datos del usuario
        $user = User::findOrFail($id);
        $user->name = $validatedData['name'];
        $user->is_admin = $validatedData['is_admin'];
    
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
    
        $user->save();
    
        return response()->json(['success' => true]);
    }

    public function deactivate($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->activo = 0;
            $user->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }



}
