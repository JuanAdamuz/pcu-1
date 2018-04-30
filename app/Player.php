<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
//    protected $connection = 'pop';

    /**
     * La primary key de la tabla del pop es uid por algún motivo...
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    /**
     * Desactivar los timestamps porque el pop no tiene esa columna.
     * Para llevar la cuenta usaré revisionable.
     *
     * @var bool
     */
    public $timestamps = false;
}
