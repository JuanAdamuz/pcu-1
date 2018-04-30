<?php

namespace App\Arma;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'pop';

    /**
     * La primary key de la tabla del pop es uid por algún motivo...
     *
     * @var string
     */
    protected $primaryKey = 'pid';

    /**
     * Desactivar los timestamps porque el pop no tiene esa columna.
     * Para llevar la cuenta usaré revisionable.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'pid', 'steamid');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'pid', 'pid');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_cliente', 'pid');
    }

    /**
     * Calcula el DNI completo con letra de control
     * https://archive.is/EIw9H.
     *
     * @return bool|string
     */
    public function getDniAttribute()
    {
        $numbers = substr($this->pid, -8);
        $resto = round($numbers % 23);
        $letter = '?';
        switch ($resto) {
            case 0:
                $letter = 'T';
                break;
            case 1:
                $letter = 'R';
                break;
            case 2:
                $letter = 'W';
                break;
            case 3:
                $letter = 'A';
                break;
            case 4:
                $letter = 'G';
                break;
            case 5:
                $letter = 'M';
                break;
            case 6:
                $letter = 'Y';
                break;
            case 7:
                $letter = 'F';
                break;
            case 8:
                $letter = 'P';
                break;
            case 9:
                $letter = 'D';
                break;
            case 10:
                $letter = 'X';
                break;
            case 11:
                $letter = 'B';
                break;
            case 12:
                $letter = 'N';
                break;
            case 13:
                $letter = 'J';
                break;
            case 14:
                $letter = 'Z';
                break;
            case 15:
                $letter = 'S';
                break;
            case 16:
                $letter = 'Q';
                break;
            case 17:
                $letter = 'V';
                break;
            case 18:
                $letter = 'H';
                break;
            case 19:
                $letter = 'L';
                break;
            case 20:
                $letter = 'C';
                break;
            case 21:
                $letter = 'K';
                break;
            default:
                $letter = 'Ñ';
        }

        return $numbers.$letter;
    }
}
