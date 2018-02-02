<?php

namespace App\Console\Commands;

use App\Answer;
use App\Name;
use App\Notifications\NameApproved;
use App\Notifications\NameChangeAvailable;
use App\Notifications\NameRejected;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ProcessReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks models with completed reviews';

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
        // Names
        $this->info('Processing names...');
        $names = Name::where('needs_review', true)->has('reviews', '>=', 3)->get();
        $names->each(function ($name) {
            $this->info('#' . $name->id . ' ' . $name->name);
            $reviews = $name->reviews()->get();
            $total = 0;
            foreach ($reviews as $review) {
                $total = $total + $review->score;
                $this->info('Review #' . $review->id . ': ' . $review->score);
            }
            $score = round($total/$reviews->count());
            $this->info('Score: ' . $total . '/' . $reviews->count() . ' = ' . $score);
            if ($score >= 51) { // Mayoría absoluta: mitad+1 para aprobar
                $name->needs_review = false;
                $name->invalid = false;
                $name->active_at = Carbon::now();

                // Antes de guardar finalizamos los demás nombres
                foreach ($name->user->names()->whereNotNull('active_at')->whereNull('end_at')->where('invalid', false)->get() as $item) {
                    $item->end_at = Carbon::now();
                    $item->save();
                }

                $name->save();
                $this->info('Nombre #' . $name->id . ' APROBADO');
                $name->user->notify(new NameApproved($name));
                if ($name->type == 'imported' && config('pcu.imported_name_changes_allow')) {
                    $user = $name->user;
                    $user->name_changes_remaining = 1;
                    $user->name_changes_reason = '@pop4';
                    $user->save();
                    $user->notify(new NameChangeAvailable());
                    Cache::forget('user.'. $user->id . '.getSetupStep');
                } else {
                    // Si no es un nombre importado le quitamos al usuario permisos de cambiarse el nombre más.
                    $user = $name->user;
                    $user->name_changes_remaining = 0;
                    $user->save();
                    Cache::forget('user.'. $user->id . '.getSetupStep');
                }
            } else {
                $name->needs_review = false;
                $name->invalid = true;
                $name->save();
                $this->info('Nombre #' . $name->id . ' SUSPENSO');
                $name->user->notify(new NameRejected($name));
                Cache::forget('user.'. $name->user->id . '.getSetupStep');
            }
        });
        // Answers de preguntas tipo texto
        $this->info('Processing text answers...');
        $answers = Answer::whereNull('score') // Que no tengan score
            ->where('needs_supervisor', false) // Que no necesiten supervisor
            ->whereHas('question', function ($query) {
 // Que exista la pregunta
                $query->where('type', 'text');
            })
            ->has('reviews', '>=', 3) // Que tenga 3 reviews o más
            ->get();
        $answers->each(function ($answer) {
            $this->info('#' . $answer->id);
            $reviews = $answer->reviews()->get();
            $total = 0;
            $abuse = false;
            foreach ($reviews as $review) {
                // Si está marcada como abuso, ponemos la answer como que necesita supervisor
                if ($review->abuse) {
                    $abuse = true;
                }
                $total = $total + $review->score;
                $this->info('Review #' . $review->id . ': ' . $review->score);
            }
            if ($abuse) {
                $this->info('#' . $answer->id . ' SUPERVISOR NECESARIO');
                $answer->needs_supervisor = true;
                $answer->needs_supervisor_reason = 'abuse';
            } else {
                $score = round($total/$reviews->count());
                $this->info('Score: ' . $total . '/' . $reviews->count() . ' = ' . $score);
                if ($score >= 50) { // Con la mitad nos vale para que le cuente.
                    $answer->score = $score;
                    $this->info('#' . $answer->id . ' APROBADO');
                } else {
                    $answer->score = 0;
                    $this->info('#' . $answer->id . ' SUSPENSO <50');
                }
            }
            $answer->save();
        });
    }
}
