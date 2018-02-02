<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Auditable;

class Answer extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    protected $dates = [
        'supervisor_at',
    ];

    public function reviews()
    {
        return $this->morphMany(\App\Review::class, 'reviewable');
    }

    public function exam()
    {
        return $this->belongsTo(\App\Exam::class);
    }

    public function question()
    {
        return $this->belongsTo(\App\Question::class);
    }

    public function supervisor() {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function getAnswerAttribute($value)
    {
        return nl2br(e($value));
    }

    public function scopeReviewable($query, $total = false)
    {
        $query->whereNotNull('answer')
            ->whereNull('score')
            ->whereHas('question', function ($query) {
                $query->where('type', 'text');
            })
            ->whereHas('exam', function ($query) {
                $query->whereNull('passed');
            })
            ->has('reviews', '<', 3);

        if (Auth::check() && !$total) {
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

    public function scopeRandom($query)
    {
        $totalRows = static::count() - 1;
        $skip = $totalRows > 0 ? mt_rand(0, $totalRows) : 0;

        $query->skip($skip)->take(1);
    }
}
