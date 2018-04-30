<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Auditable;

class Name extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();
    }

    public function scopeRandom($query)
    {
        $totalRows = static::count() - 1;
        $skip = $totalRows > 0 ? mt_rand(0, $totalRows) : 0;

        $query->skip($skip)->take(1);
    }

    public function reviews()
    {
        return $this->morphMany(\App\Review::class, 'reviewable');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    protected $dates = [
        'active_at',
        'end_at',
    ];

    public function scopeActive($query)
    {
        return $query->
            whereNotNull('active_at')
            ->where('invalid', 0)
            ->where('needs_review', false)
            ->whereNull('end_at');
    }

    public function scopeReviewable($query, $total = false)
    {
        $query->where('needs_review', true)
            ->has('reviews', '<', 3);

        if (Auth::check() && ! $total) {
            $query->whereDoesntHave('reviews', function ($query) use ($total) {
                $query->where('user_id', Auth::user()->id);
            });
        } else {
            $query->whereDoesntHave('reviews', function ($query) use ($total) {
                $query->where('user_id', 0);
            });
        }

        return $query;
    }
}
