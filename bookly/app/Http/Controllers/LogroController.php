<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LogroController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $logros = $user->logros;
        
        return view('logros.index', compact('logros'));
    }
}