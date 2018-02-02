<?php

namespace App\Http\Controllers\Mod;

use App\Name;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! Auth::user()->hasPermission(['mod-search'])) {
            abort(403);
        }

        $results = Name::query();

        if ($request->has('q')) {
            $results->orWhere('name', 'LIKE', '%' . $request->input('q') . '%');
        }

        if ($request->has('type')) {
            $results->where('type', $request->input('type'));
        }

        $results->orderBy('updated_at', 'desc');
        $results->with('user');
        $results = $results->paginate(15);
        return view('mod.names.index')->with('results', $results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Name  $name
     * @return \Illuminate\Http\Response
     */
    public function show(Name $name)
    {
        if (! Auth::user()->hasPermission(['mod-search'])) {
            abort(403);
        }

        $name->load('reviews', 'user');
        return view('mod.names.show')->with('name', $name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Name  $name
     * @return \Illuminate\Http\Response
     */
    public function edit(Name $name)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Name  $name
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Name $name)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Name  $name
     * @return \Illuminate\Http\Response
     */
    public function destroy(Name $name)
    {
        abort(404);
    }
}
