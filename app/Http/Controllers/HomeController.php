<?php

namespace App\Http\Controllers;

use App\Page;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'setup_required'])->except(['tos', 'monetization', 'privacy']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Cache::remember('home.posts', 5, function() {
            return Post::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        });
        $opening = Carbon::parse(config('pcu.pop_opening'));
        $updated = [];
        $updated['rules'] = Cache::remember('home.updated.rules', 5, function() {
            $page = Page::where('slug', 'normas')->first();
            if(is_null($page)) {
                return Carbon::now()->subDays(100);
            }
            return $page->updated_at >= Carbon::now()->subDay();
        });
        $updated['download'] = Cache::remember('home.updated.download', 5, function() {
            $page = Page::where('slug', 'descargas')->first();
            if(is_null($page)) {
                return Carbon::now()->subDays(100);
            }
            return $page->updated_at >= Carbon::now()->subDay();
        });
        return view('home')
            ->with('user', Auth::user())
            ->with('player', Auth::user())
            ->with('opening', $opening)
            ->with('posts', $posts)
            ->with('updated', $updated);
    }

    public function rules()
    {
        return view('setup.rules');
    }

    public function tos()
    {
        return view('policy.tos');
    }

    public function monetization()
    {
        return view('policy.monetization');
    }

    public function privacy()
    {
        return view('policy.privacy');
    }

    public function about()
    {
        return view('policy.about');
    }
}
