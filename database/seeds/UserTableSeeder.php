<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'steamid' => '76561198053789373',
            'email_enabled' => 0,
            'email_verified' => 0,
            'has_game' => 1,
            'birth_date' => '1998-02-26',
            'country' => 'ES',
            'timezone' => 'Europe/Madrid',
            'rules_seen_at' => '2017-01-01 00:00',
            'imported_exam_exempt' => 1,
            'admin' => 1,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $name = new \App\Name();
        $name->user_id = 1;
        $name->name = "Manolo Pérez";
        $name->needs_review = 0;
        $name->active_at = \Carbon\Carbon::now();
        $name->save();
    }
}
