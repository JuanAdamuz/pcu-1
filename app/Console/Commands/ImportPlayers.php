<?php

namespace App\Console\Commands;

use App\Name;
use App\Notifications\AccountImported;
use App\Notifications\NameApproved;
use App\Notifications\NameChangeAvailable;
use App\Notifications\NameRejected;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportPlayers extends Command
{

    private $correct = [
        // Nombres
        'Raul' => 'Raúl',
        'Oscar' => 'Óscar',
        'Alvaro' => 'Álvaro',
        'Andres' => 'Andrés',
        'Angel' => 'Ángel',
        'Jesus' => 'Jesús',
        'Adrian' => 'Adrián',
        'Guzman' => 'Guzmán',
        'Ivan' => 'Iván',
        'Sebastian' => 'Sebastián',
        'Ruben' => 'Rubén',
        'Julian' => 'Julián',
        'Fermin' => 'Fermín',
        'Cesar' => 'César',
        'Matias' => 'Matías',
        'Agustin' => 'Agustín',
        'Joaquin' => 'Joaquín',
        'Martin' => 'Martín',
        'Tobias' => 'Tobías',
        // Apellidos
        'Rodriguez' => 'Rodríguez',
        'Hernandez' => 'Hernández',
        'Fernandez' => 'Fernández',
        'Martinez' => 'Martínez',
        'Gonzalez' => 'González',
        'Gonzales' => 'González',
        'Garcia' => 'García',
        'Casarin' => 'Casarín',
        'Benitez' => 'Benítez',
        'Gomez' => 'Gómez',
        'Sanchez' => 'Sánchez',
        'Lopez' => 'López',
        'Perez' => 'Pérez',
        'Marquez' => 'Márquez',
        'Gutierrez' => 'Gutiérrez',
        'Diaz' => 'Díaz',
        'Avila' => 'Ávila',
        'Suarez' => 'Suárez',
        'Ramirez' => 'Ramírez',
        'Beltran' => 'Beltrán',
        'Ibañez' => 'Ibáñez',
        'Vazquez' => 'Vázquez',
        'Millan' => 'Millán',
        'Lazaro' => 'Lázaro',
        'Cardenas' => 'Cárdenas',
        // Diminutivos

        // Troll
        'Yesus' => 'Jesús',
        'Yisus' => 'Jesús',
        'Jesulín' => 'Jesús',
    ];

    /**
     * Jugadores que tienen que repetir el examen.
     * @var array
     */
    private $repeat = [
        // steamid => motivo (quitado por privacidad)
    ];

    private $except = [
        'Ivánov' => 'Ivanov',
        'Ivánero' => 'Ivanero'
    ];



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports players from another version';

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
        $players = DB::table('players')->get();
        $this->info('Encontrados ' . $players->count() . ' jugadores para importar');
        $bar = $this->output->createProgressBar($players->count());
        $count = 0;
        foreach ($players as $player) {
            $user = new User();
            $user->steamid = $player->playerid;
            $user->has_game = true; // Tiene el juego seguro
            $user->imported = Carbon::now(); // Marcarlo como importado
            if (key_exists($user->steamid, $this->repeat)) {
                $user->imported_exam_exempt = false; // a repetir el examen
                $user->imported_exam_message = $this->repeat[$user->steamid];
            } else {
                $user->imported_exam_exempt = true; // no tiene que hacer el examen
            }
            $user->save();
            // Generamos un nombre y lo marcamos como importado
            $name = new Name();
            $correctedName = rtrim($this->correctSpelling($this->titleCase(str_replace('´', '', $player->name))));
            if ($correctedName != $player->name) {
                $name->original_name = $player->name;
            }
            $name->name = $correctedName; // Corrección de faltas de ortografía
            $name->type = 'imported';
            if ($player->adminlevel == 0) {
                // Si no tiene espacios o tiene caracteres raros... está mal.
                if (substr_count($correctedName, ' ') == 0 || substr_count($correctedName, '.') > 0) {
                    $name->needs_review = false;
                    $name->invalid = true;
                    $user->names()->save($name); // guardarlo y rechazarlo, notificando al usuario
                    $name->user->notify(new NameRejected($name));
                } else {
                    // Nombre correcto. No hacemos nada especial. Se revisará.
                    $name->needs_review = true;
                    $name->invalid = false;
                }
            } else {
                // Si era admin, aceptar el nombre inmediatamente e informarles.
                $name->needs_review = false;
                $name->invalid = false;
                $name->active_at = Carbon::now();
                $user->names()->save($name); // guardarlo
                $name->user->notify(new NameApproved($name));
                $user = $name->user;
                $user->name_changes_remaining = 1;
                $user->name_changes_reason = '@pop4';
                $user->save();
                $user->notify(new NameChangeAvailable());
            }
            // $name->active_at = Carbon::now(); Al final queremos que los nombres pasen revisión.
            $user->names()->save($name); // guardarlo

            // Notificarle de que le hemos importado
            $user->notify(new AccountImported());

            // GUID
            $guid = $user->guid; // generamos la GUID para poder buscar por GUID
            $count++;
            $bar->advance();
        }
        $bar->finish();
    }

    public function correctSpelling($name)
    {
        return strtr(strtr($name, $this->correct), $this->except);
    }

    /**
     * http://php.net/manual/es/function.ucwords.php#112795
     * @param $string
     * @param array $delimiters
     * @param array $exceptions
     * @return mixed|string
     */
    function titleCase($string, $delimiters = [" ", "-", ".", "'", "O'", "Mc"], $exceptions = ["de", "da", "dos", "das", "do", "del", "I", "II", "III", "IV", "V", "VI"])
    {
        /*
         * Exceptions in lower case are words you don't want converted
         * Exceptions all in upper case are any words you don't want converted to title case
         *   but should be converted to upper case, e.g.:
         *   king henry viii or king henry Viii should be King Henry VIII
         */
        $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = [];
            foreach ($words as $wordnr => $word) {
                if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, "UTF-8");
                } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, "UTF-8");
                } elseif (!in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
        }//foreach
        return $string;
    }
}
