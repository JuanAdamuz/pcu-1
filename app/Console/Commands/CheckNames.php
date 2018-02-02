<?php

namespace App\Console\Commands;

use App\Arma\Player;
use App\User;
use Illuminate\Console\Command;

class CheckNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'names:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all names';

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
        $players = Player::with('user')->get();

        $bar = $this->output->createProgressBar(count($players));

        foreach ($players as $player) {
            if(is_null($player->user)) {
                continue;
            }
            if(!is_null($player->user->name)) {
                continue;
            }
            $name = $player->user->getActiveName();
            if(!is_null($name) && $name != $player->name) {
                $player->name = $name;
                $player->save();
                $this->info($player->name .  " > > " . $name);
            }
            $bar->advance();
        }

        $bar->finish();
    }
}
