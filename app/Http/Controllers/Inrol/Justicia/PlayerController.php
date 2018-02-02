<?php

namespace App\Http\Controllers\Inrol\Justicia;

use App\Arma\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlayerController extends Controller
{
    public function search(Request $request) {

        $results = Player::query();

        $this->validate($request, [
            'q' => 'nullable|min:3|max:17',
        ],[
            'q.min' => 'Introduzca al menos :min caracteres.',
            'q.max' => 'Introduzca como máximo :max caracteres.',
        ]);

        $results->where('name', 'LIKE', '%'.$request->input('q').'%');
        $results->orWhere('pid', 'LIKE', '%'.$request->input('q').'%');

        $results = $results->paginate(15);

        return view('inrol.justicia.personas.search')
            ->with('results', $results);
    }
}
