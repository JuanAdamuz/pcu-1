<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserRelatedPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            'name' => 'mod-name-reject',
            'display_name' => 'Marcar nombre como inválido',
            'description' => 'Marca un nombre como inválido, obligando al usuario a cambiárselo.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-name-accept',
            'display_name' => 'Marcar nombre como aceptado',
            'description' => 'Marca un nombre como aceptado.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-name-reviewers',
            'display_name' => 'Ver revisores de un nombre',
            'description' => 'Ver quién ha revisado un nombre y su votación.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
