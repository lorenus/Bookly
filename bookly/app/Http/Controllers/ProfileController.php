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

        $ultimosLogros = $user->logros()
            ->withPivot('completado_en')
            ->orderBy('logro_user.completado_en', 'desc')
            ->take(3)
            ->get();

        return view('profile.show', [
            'user' => $user,
            'leyendoActual' => $leyendoActual,
            'paraLeer' => $paraLeer,
            'ultimasLecturas' => $ultimasLecturas,
            'ultimosLogros' => $ultimosLogros
        ])->with('success', 'Perfil actualizado correctamente');
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
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'retoAnual' => 'nullable|integer|min:1|max:100',
            'lista_a_borrar' => 'nullable|in:leyendo,leido,porLeer,favoritos'
        ]);

        if ($request->hasFile('imgPerfil')) {
            try {

                if ($user->imgPerfil && $user->imgPerfil !== 'profile-photos/default.jpg') {
                    Storage::disk('public')->delete($user->imgPerfil);
                }

                // Guardar nueva imagen
                $path = $request->file('imgPerfil')->store('profile-photos', 'public');
                $user->imgPerfil = $path;
            } catch (\Exception $e) {
                return back()->with('error', 'Error al guardar la imagen: ' . $e->getMessage());
            }
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('retoAnual')) {
            $user->retoAnual = $request->retoAnual;
        }

        $user->save();

        if ($request->lista_a_borrar) {
            $user->libros()->wherePivot('estado', $request->lista_a_borrar)->detach();
        }

        // Forzar actualización de caché con timestamp
        return redirect()->route('perfil', ['v' => time()])
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
