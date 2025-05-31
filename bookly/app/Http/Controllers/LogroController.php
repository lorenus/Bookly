<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Logro;
use App\Models\User;

class LogroController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $logros = Logro::all(); 

        $userLogrosIds = $user->logros->pluck('id')->toArray();
        
        dd($logros, $userLogrosIds);

        return view('logros.index', compact('logros', 'userLogrosIds'));
    }
}