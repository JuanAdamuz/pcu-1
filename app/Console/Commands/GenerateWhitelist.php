<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GenerateWhitelist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whitelist:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate whitelist file';

    /**
     * Create a new command instance.
     *
     * @return void
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

        $whitelist = null;

        $users = User::where('disabled', false)
            ->whereNotNull('birth_date')
            ->whereNotNull('country')
            ->whereNotNull('timezone')
            ->whereNotNull('ipb_id')
            ->orWhereNotNull('name')->get();
        $count = 0;
        $bar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            if (!$user->hasFinishedSetup() && is_null($user->name)) {
                continue;
            }
            if (is_null($whitelist)) {
                $whitelist = $user->guid .' ' . $user->id;
            } else {
                $whitelist = $whitelist . "\n" . $user->guid .' ' . $user->id;
            }
            $count++;
            if (!request()->has('noupdate') && is_null($user->whitelist)) {
                $user->whitelist_at = Carbon::now();
                $user->save();
            }
            $bar->advance();
        }
        $bar->finish();

        Cache::forget('whitelist');
        Cache::forever('whitelist', $whitelist);

        $this->info("\nWhitelist procesada: " . $count);
    }
}
