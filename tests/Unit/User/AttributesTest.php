<?php

namespace Tests\Unit\User;

use App\Name;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AttributesTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * test el atributo GUID.
     */
    public function testGUIDConversion()
    {
        $user = factory(User::class)->make([
            'steamid' => '76561198053789373',
        ]);
        $this->assertEquals('f9d6b3ef5b542faced6353e7fa69f4b7', $user->guid);
    }

    /**
     * Test el atributo de Username
     * Si no tiene nombre o alias, es la SteamID
     * Si tiene un alias, es el alias
     * Si tiene un nombre activo, es el nombre activo más reciente.
     */
    public function testUsernameAttribute()
    {
        $user = factory(User::class)->create([
            'steamid' => '76561198053789373',
        ]);
        // Al principio, debería salir la SteamID
        $this->assertEquals('76561198053789373', $user->username);

        // Comprobar si con un nombre inválido se mantiene
        factory(Name::class)->create([
            'user_id' => $user->id,
        ]);
        $this->assertEquals('76561198053789373', $user->username);

        // Nombre activo, debería sustituir a la SteamID
        $name = factory(Name::class)->states('active')->create([
            'name'    => 'Manolo Pérez',
            'user_id' => $user->id,
        ]);
        $this->assertEquals('Manolo Pérez', $user->username);

        // Comprobar si tiene un alias
        $user->name = 'Apecengo';
        $user->save();
        $this->assertEquals('Apecengo', $user->username);
    }

    /**
     * Test el DNI.
     */
    public function testDNIAttribute()
    {
        $user = factory(User::class)->create([
            'steamid' => '76561198053789373',
        ]);
        $this->assertEquals('3789373P', $user->dni);
    }
}
