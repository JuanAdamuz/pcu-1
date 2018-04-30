<?php

namespace App\Http\Controllers;

use Cviebrock\DiscoursePHP\SSOHelper;
use Illuminate\Http\Request;

class DiscourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'setup_required']);
    }

    public function sso(Request $request)
    {
        $secret = config('pcu.discourse_secret');
        if (is_null($secret)) {
            abort(404);
        }

        $sso = new SSOHelper();
        // this should be the same in your code and in your Discourse settings:
        $sso->setSecret($secret);

        // load the payload passed in by Discourse
        $payload = $_GET['sso'];
        $signature = $_GET['sig'];

        // validate the payload
        if (! ($sso->validatePayload($payload, $signature))) {
            // invaild, deny
            abort(403);
        }

        $nonce = $sso->getNonce($payload);

        // Insert your user authentication code here ...
        $user = \Auth::user();

        // Required and must be unique to your application
        $userId = $user->id;

        // Required and must be consistent with your application
        $userEmail = $user->email;

        if (is_null($userEmail) || ! $user->email_verified) {
            return view('errors.emailerror');
        }

        // Optional - if you don't set these, Discourse will generate suggestions
        // based on the email address

        $name = null;

        if (! is_null($user->name)) {
            $name = $user->name;
        } else {
            $name = $user->getActiveName();
        }

        $extraParameters = [
            'username'           => strtolower(str_replace(' ', '', $name)),
            'name'               => $name,
            'require_activation' => true,
        ];

        // build query string and redirect back to the Discourse site
        $query = $sso->getSignInString($nonce, $userId, $userEmail, $extraParameters);
        header('Location: https://foro.poplife.wtf/session/sso_login?'.$query);
        exit(0);
    }
}
