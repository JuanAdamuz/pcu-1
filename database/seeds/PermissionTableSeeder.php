<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*
         * Permisos de usuario.
         */
        DB::table('permissions')->insert([
            'name' => 'user-abilities-view',
            'display_name' => 'Ver permisos propios',
            'description' => 'Permite ver los permisos y grupos de uno mismo.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        /*
         * Moderación
         */
        DB::table('permissions')->insert([
            'name' => 'mod-search',
            'display_name' => 'Mod: búsqueda de usuarios',
            'description' => 'Permite acceder al panel del moderador y buscar usuarios.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-review-answers',
            'display_name' => 'Revisar respuestas',
            'description' => 'Permite revisar respuestas.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-review-names',
            'display_name' => 'Revisar nombres',
            'description' => 'Permite revisar nombres.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-interview',
            'display_name' => 'Entrevistar',
            'description' => 'Permite entrevistar usuarios.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-intervier',
            'display_name' => 'Entrevistador',
            'description' => 'Marca como entrevistador y permite establecer un campo con disponibilidad.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-reveal-birthdate',
            'display_name' => 'Ver fecha de nacimiento',
            'description' => 'Permite revelar la fecha de nacimiento de un usuario.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-reveal-email',
            'display_name' => 'Ver correo electrónico',
            'description' => 'Permite revelar el correo electrónico de un usuario.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('permissions')->insert([
            'name' => 'mod-supervise-answers',
            'display_name' => 'Supervisar respuestas',
            'description' => 'Permite supervisar respuestas.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-supervise-names',
            'display_name' => 'Supervisar nombres',
            'description' => 'Permite supervisar nombres.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
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
        DB::table('permissions')->insert([
            'name' => 'mod-exam-answers',
            'display_name' => 'Ver las respuestas de los exámenes',
            'description' => 'Ver un detalle con el examen de un usuario.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-exam-answers-reviews',
            'display_name' => 'Ver revisores de una respuesta',
            'description' => 'Ver quién ha revisado una respuesta y su votación.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-exam-suspend',
            'display_name' => 'Suspender un examen',
            'description' => 'Suspender un examen antes de que sea corregido.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-exam-extend',
            'display_name' => 'Extender la fecha de un examen',
            'description' => 'Permite añadir días antes de que un examen expire.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'mod-exam-interview-cancel',
            'display_name' => 'Cancelar una entrevista',
            'description' => 'Permite cancelar una entrevista en curso.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);





        /*
         * Sistema de usuarios "protegidos"
         */
        DB::table('permissions')->insert([
            'name' => 'protection-level-1',
            'display_name' => 'Protección de nivel 1',
            'description' => 'Protección de nivel 1.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name' => 'protection-level-1-bypass',
            'display_name' => 'Permisos de nivel 1',
            'description' => 'Permisos de nivel 1.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
