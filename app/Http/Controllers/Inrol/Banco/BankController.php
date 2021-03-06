<?php

namespace App\Http\Controllers\Inrol\Banco;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['integration_required']);
    }

    public function viewAccounts()
    {
        return view('inrol.banco.cuentas')
            ->with('user', Auth::user())
            ->with('player', Auth::user()->player);
    }
}
