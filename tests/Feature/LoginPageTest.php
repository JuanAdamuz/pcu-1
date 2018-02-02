<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginPageTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Comprobar si la página principal funciona.
     *
     * @return void
     */
    public function testLoginPageWorks()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_it_redirects_to_login_if_visits_home_logged_out()
    {
        $response = $this->get('/home');
        $response->assertRedirect('/login');
    }
}
