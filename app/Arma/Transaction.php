<?php

namespace App\Arma;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'pop';

    protected $table = 'movimientos_bancarios';

    protected $dates = [
        'timestamp',
    ];

    /**
     * Desactivar los timestamps porque el pop no tiene esa columna.
     * Para llevar la cuenta usaré revisionable.
     *
     * @var bool
     */
    public $timestamps = false;

    public function player()
    {
        $this->belongsTo(Player::class, 'id_cliente', 'pid');
    }

    public function getTypeName()
    {
        $return = 'Otro';
        switch ($this->tipo) {
            case 0:
                $return = 'Retirada';
                break;
            case 1:
                $return = 'Ingreso';
                break;
            case 2:
                $return = 'Pago tarjeta';
                break;
            case 3:
                $return = 'Otros';
                break;
            case 4:
                $return = 'Transferencia';
                break;
            case 5:
                $return = 'Transferencia';
                break;
            default:
                $return = 'Otros';
        }

        return $return;
    }
}
