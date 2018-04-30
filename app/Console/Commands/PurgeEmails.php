<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurgeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge unvalidated emails';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Purging unvalidated emails...');
        // Usuarios cuyo código de verificación tenga más de 24h de antigüedad.
        $users = User::whereNotNull('email_verified_token_at')
            ->where('email_verified_token_at', '<=', Carbon::now()->subDay())
            ->get();
        $count = 0;
        foreach ($users as $user) {
            $user->email = null; // Por si acaso se lo borramos
            $user->email_verified = false;
            $user->email_verified_token = null;
            $user->email_verified_token_at = null;
            $user->email_verified_at = null;
            $user->email_enabled = false;
            $user->email_prevent = false;
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            ++$count;
        }
        $this->info('Purgados '.$count.' correos no validados.');
    }
}
