<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User; // Asegúrate de importar el modelo User

class LogroController extends Controller
{
    public function index()
    {
        // Opción 1 (recomendada):
        $user = Auth::user();
        $logros = $user->logros; // Usa la propiedad, no el método
        
        // Opción 2 (si prefieres usar métodos):
        // $logros = auth()->user()->logros()->get();
        
        return view('logros.index', compact('logros'));
    }
}