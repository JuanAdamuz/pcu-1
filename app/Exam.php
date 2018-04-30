<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class Exam extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes;
    use Auditable;

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(\App\User::class, 'interview_user_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(\App\Answer::class);
    }

    protected $hidden = [
      'interview_at', 'interview_audio_encoded_at', 'interview_audio_message',
        'interview_audio_url', 'interview_passed', 'interview_user_id',
        'passed_message', 'passed_temporal', 'review_at', 'review_required',
        'review_user_id', 'score', 'interview_code',
    ];

    protected $dates = [
        'end_at',
        'start_at',
        'expires_at',
        'passed_at',
        'interview_at',
        'finish_at',
        'interview_code_at',
        'deleted_at',
        'interview_end_at',
    ];

    protected $casts = [
        'structure' => 'array',
    ];

    public function getQuestionCount()
    {
        $count = 0;
        foreach ($this->structure as $group) {
            $count = $count + sizeof($group['questions']);
        }

        return $count;
    }

    public function getCurrentQuestionNumber()
    {
        $current = 1;
        foreach ($this->structure as $group) {
            foreach ($group['questions'] as $question) {
                if (is_null($question['answer_id'])) {
                    return $current;
                } else {
                    ++$current;
                }
            }
        }
    }

    public function getTotalQuestionValue()
    {
        $total = 0;
        foreach ($this->structure as $group) {
            foreach ($group['questions'] as $question) {
                $total = $total + $question['value'];
            }
        }

        return $total;
    }

    public function isFinished()
    {
        if (\Carbon\Carbon::now() > $this->end_at) {
            return true;
        }
        foreach ($this->structure as $group) {
            foreach ($group['questions'] as $question) {
                if (is_null($question['answer_id'])) {
                    return true;
                }
            }
        }

        return false;
    }
}
