<?php

return [
    /*
     * Redirect URL after login
     */
    'redirect_url' => '/login',
    /*
     * API Key (http://steamcommunity.com/dev/apikey)
     */
    'api_key' => env('STEAM_KEY', null),
    /*
     * Is using https ?
     */
    'https' => false,
];
