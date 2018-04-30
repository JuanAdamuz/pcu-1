<?php

namespace App\Console\Commands;

use App\Exam;
use App\Notifications\ExamExpired;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurgeExams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:exams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge expired, pending exams';

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
        $this->info('Purgando exámenes expirados y pendientes...');
        $exams = Exam::whereNull('interview_passed')
            ->whereNull('interview_user_id')
            ->where('passed', true)
            ->where('expires_at', '<', Carbon::now())
            ->get();
        $count = 0;
        foreach ($exams as $exam) {
            $exam->user->notify(new ExamExpired($exam));
            $exam->delete();
            ++$count;
        }
        $this->info('Purgados '.$count.' exámenes expirados.');
    }
}
