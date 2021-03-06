<?php

namespace App\Http\Controllers;

use App\Arma\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Arma\Server $server
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Arma\Server $server
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Server $server)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Arma\Server         $server
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Server $server)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Arma\Server $server
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server)
    {
    }

    public function json()
    {
        return Cache::remember('poplifetxt', 60, function () {
            $return = [];
            $servers = Server::all();
            foreach ($servers as $server) {
                $return[] = [
                    'ip_address' => $server->ip,
                    'port'       => ''.$server->port,
                ];
            }

            return $return;
        });
    }
}
