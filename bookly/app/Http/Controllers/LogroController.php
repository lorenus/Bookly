<?php
namespace App\Http\Controllers;

use App\Models\Logro;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LogroController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Obtener todos los logros con su estado para el usuario
        $logros = Logro::with(['users' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get()->map(function($logro) use ($user) {
            $logro->pivot = $logro->users->first()?->pivot ?? null;
            return $logro;
        });
        
        return view('logros.index', compact('logros'));
    }
}