<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use OwenIt\Auditing\Audit;

class PruneAudits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prune:audits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune old audits';

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
        $date = Carbon::now()->subDays(30);
        $audits = \OwenIt\Auditing\Models\Audit::where('created_at', '<', $date)->delete();
    }
}
