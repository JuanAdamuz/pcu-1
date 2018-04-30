<?php

namespace Tests\Feature;

use Tests\TestCase;

class PolicyPageTest extends TestCase
{
    /**
     * Comprobar que la página de términos y condiciones funciona.
     */
    public function testTosPageWorks()
    {
        $response = $this->get('/tos');
        $response->assertStatus(200);
        $response->assertViewIs('policy.tos');
    }

    /**
     * Comprobar que la página de la Política de Privacidad funciona.
     */
    public function testPrivacyPageWorks()
    {
        $response = $this->get('/privacy');
        $response->assertStatus(200);
        $response->assertViewIs('policy.privacy');
    }

    /**
     * Comprobar que la página de la Política de Monetización funciona.
     */
    public function testMonetizationPageWorks()
    {
        $response = $this->get('/monetization');
        $response->assertStatus(200);
        $response->assertViewIs('policy.monetization');
    }
}
