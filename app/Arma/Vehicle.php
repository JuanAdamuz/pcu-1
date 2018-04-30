<?php

namespace App\Arma;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'pop';

    /**
     * Desactivar los timestamps porque el pop no tiene esa columna.
     * Para llevar la cuenta usaré revisionable.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $dates = [
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'pid', 'pid');
    }

    public function scopeCivilian($query)
    {
        return $query->where('side', 'civ');
    }

    public function scopeMed($query)
    {
        return $query->where('side', 'med');
    }

    public function scopeCop($query)
    {
        return $query->where('side', 'cop');
    }

    public function scopeCar($query)
    {
        return $query->where('type', 'Car');
    }

    public function scopeAir($query)
    {
        return $query->where('type', 'Air');
    }

    public function scopeShip($query)
    {
        return $query->where('type', 'Ship');
    }

    public function getNameAttribute()
    {
        if (key_exists($this->classname, config('pop.vehicles'))) {
            return config('pop.vehicles')[$this->classname]['name'];
        }

        return $this->classname;
    }

    public function getPriceAttribute()
    {
        if (key_exists($this->classname, config('pop.vehicles'))) {
            return config('pop.vehicles')[$this->classname]['price'];
        }

        return null;
    }

    public function isTransferable()
    {
        // No permitir aeronaves, embarcaciones ni vehículos de EMS/Policía
        if ('Car' != $this->type || 'civ' != $this->side) {
            return false;
        }

        // Mínimo tiempo de matriculación para transferir una semana
        if ($this->insert_time > Carbon::now()->subWeek()) {
            return false;
        }

        // El dueño debe tener dos semanas jugadas
        if ($this->player->insert_time > Carbon::now()->subWeeks(2)) {
            return false;
        }

        // Si el vehículo ha explotado (siniestrado) no dejamos
        if (! $this->alive) {
            return false;
        }

        // Si no está en la lista, no dejamos transferirlo
        if (! key_exists($this->classname, config('pop.vehicles'))) {
            return false;
        }

        return true;
    }
}
