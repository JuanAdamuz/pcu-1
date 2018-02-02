<?php

namespace App\Console\Commands;

use App\Answer;
use App\Exam;
use App\Jobs\GradeAnswer;
use App\Jobs\GradeExam;
use App\Notifications\ExamFailed;
use App\Notifications\ExamPassed;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GradeExams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:grade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grade all available exams';

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
        $this->info('Corrigiendo exámenes...');
        $exams = Exam::whereNull('passed')
            ->where(function ($query) {
                $query->where('end_at', '<', Carbon::now())
                    ->orWhere('finished', true);
            })
            ->get();
        foreach ($exams as $exam) {
            $this->info('Corrigiendo examen #' . $exam->id);
            dispatch(new GradeExam($exam));
        }
        if ($exams->count() == 0) {
            $this->info('Nada que corregir.');
        }
    }
}
