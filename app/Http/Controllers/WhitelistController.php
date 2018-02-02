<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class WhitelistController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'admin'])->except(['download']);
    }

    public function whitelistPage()
    {
        $last = User::whereNotNull('whitelist_at')->latest()->first();
        if (!is_null($last)) {
            $last = $last->whitelist_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i');
        } else {
            $last = null;
        }
        return view('whitelist')->with('last');
    }

    public function download()
    {
        if(!Auth::check() || !Auth::user()->isAdmin()) {
            if(!\request()->has('key') || \request()->input('key') != config('pcu.whitelist_key')) {
                abort(403);
            }
        }
        
        if(!Cache::has('whitelist')) {
            abort(404);
        }

        $whitelist = Cache::get('whitelist');

        return \response($whitelist, 200)->header('Content-Type', 'text/plain');
//        $headers = ['Content-type'=>'text/plain', 'Content-Disposition attachment; filename="WhiteList.txt"','Content-Length'=>sizeof($whitelist)];
//        return Response::make($whitelist, 200, $headers);
    }
}
