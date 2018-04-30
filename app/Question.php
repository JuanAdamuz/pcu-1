<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class Question extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    protected $casts = [
        'options' => 'array',
    ];

    protected $hidden = [
        'category_id',
        'answers',
        'score',
    ];
}
