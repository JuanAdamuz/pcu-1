<?php
/**
 * Copyright (c) 2017. Apecengo
 * Todos los derechos reservados.
 * No se permite la copia, distribución o reproducción por ningún medio.
 * Para más información sobre usos permitidos, ver el archivo LICENSE.md.
 */
use Illuminate\Database\Migrations\Migration;

class AddModExamRelatedPermissions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-answers',
            'display_name' => 'Ver las respuestas de los exámenes',
            'description'  => 'Ver un detalle con el examen de un usuario.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-answers-reviews',
            'display_name' => 'Ver revisores de una respuesta',
            'description'  => 'Ver quién ha revisado una respuesta y su votación.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-suspend',
            'display_name' => 'Suspender un examen',
            'description'  => 'Suspender un examen antes de que sea corregido.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-extend',
            'display_name' => 'Extender la fecha de un examen',
            'description'  => 'Permite añadir días antes de que un examen expire.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-interview-cancel',
            'display_name' => 'Cancelar una entrevista',
            'description'  => 'Permite cancelar una entrevista en curso.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
