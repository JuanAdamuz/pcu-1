<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PolicyPageTest extends TestCase
{
    /**
     * Comprobar que la página de términos y condiciones funciona
     *
     * @return void
     */
    public function testTosPageWorks()
    {
        $response = $this->get('/tos');
        $response->assertStatus(200);
        $response->assertViewIs('policy.tos');
    }

    /**
     * Comprobar que la página de la Política de Privacidad funciona
     *
     * @return void
     */
    public function testPrivacyPageWorks()
    {
        $response = $this->get('/privacy');
        $response->assertStatus(200);
        $response->assertViewIs('policy.privacy');
    }

    /**
     * Comprobar que la página de la Política de Monetización funciona
     *
     * @return void
     */
    public function testMonetizationPageWorks()
    {
        $response = $this->get('/monetization');
        $response->assertStatus(200);
        $response->assertViewIs('policy.monetization');
    }
}
