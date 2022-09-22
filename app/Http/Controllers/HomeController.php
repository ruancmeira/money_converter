<?php

namespace App\Http\Controllers;

use App\Models\Cotacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cotacoes = Cotacao::orderByRaw('updated_at DESC')->where('usuario', '=', Auth::user()->id)->simplePaginate(7);

        return view('home')->with([
            'cotacoes' => $cotacoes
        ]);
    }
}
