<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Amistad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::with('libros')->find(Auth::id());

        $leyendoActual = $user->libros()
            ->wherePivot('estado', 'leyendo')
            ->orderBy('libros_usuario.updated_at', 'desc')
            ->take(4)
            ->get();

        $paraLeer = $user->libros()
            ->wherePivot('estado', 'porLeer')
            ->orderBy('libros_usuario.updated_at', 'desc')
            ->take(4)
            ->get();

        $ultimasLecturas = $user->libros()
            ->wherePivot('estado', 'leido')
            ->orderBy('libros_usuario.updated_at', 'desc')
            ->take(4)
            ->get();

        return view('profile.show', [
            'user' => $user,
            'leyendoActual' => $leyendoActual,
            'paraLeer' => $paraLeer,
            'ultimasLecturas' => $ultimasLecturas
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'imgPerfil' => 'nullable|image|max:2048',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'retoAnual' => 'nullable|integer|min:1|max:100',
            'lista_a_borrar' => 'nullable|in:leyendo,leido,porLeer,favoritos'
        ]);

        if ($request->hasFile('imgPerfil')) {
            // Eliminar solo si NO es la imagen por defecto
            if ($user->imgPerfil !== 'profile-photos/default.jpg') {
                Storage::disk('public')->delete($user->imgPerfil);
            }

            $user->imgPerfil = $request->file('imgPerfil')->store('profile-photos', 'public');
            $user->save();
        }

        // Actualizar email
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null; // Si usas verificación de email
        }

        // Actualizar contraseña si se proporcionó
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Actualizar reto anual
        $user->retoAnual = $request->retoAnual;

        $user->save();

        // Vaciar lista seleccionada si se especificó
        if ($request->lista_a_borrar) {
            $user->libros()
                ->wherePivot('estado', $request->lista_a_borrar)
                ->detach();
        }

        // Forzar recarga de la sesión
        Auth::login($user);

        // Redirigir con parámetro de cache busting para imágenes
        return redirect()->route('perfil', ['v' => now()->timestamp])
            ->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada correctamente');
    }

    public function amigo(User $user)
    {
        // Verificar relación de amistad
        $esAmigo = Amistad::where('estado', 'aceptada')
            ->where(function ($query) use ($user) {
                $query->where('user_id', Auth::id())
                    ->where('amigo_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('amigo_id', Auth::id());
            })
            ->exists();

        if (!$esAmigo) {
            abort(403, 'No tienes permiso para ver este perfil');
        }

        return view('profile.amigo', [
            'amigo' => $user,
            'librosLeidos' => $user->libros()->wherePivot('estado', 'leido')->count(),
            'librosLeyendo' => $user->libros()->wherePivot('estado', 'leyendo')->count()
        ]);
    }
}
