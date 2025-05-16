<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Logro;

class LogroController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $logros = Logro::with(['users' => function($query) use ($user) {
        $query->where('user_id', $user->id);
    }])->get();
    
    return view('logros.index', compact('logros'));
}
}