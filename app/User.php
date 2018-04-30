<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laratrust\Traits\LaratrustUserTrait;
use OwenIt\Auditing\Auditable;

class User extends Authenticatable implements \OwenIt\Auditing\Contracts\Auditable
{
    use LaratrustUserTrait;
    use Notifiable;
    use Auditable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql';

    protected $dates = [
        'disabled_at',
        'rules_seen_at',
        'birth_date',
        'email_verified_at',
        'email_verified_token_at',
        'email_disabled_at',
        'whitelist_at',
    ];

    protected $settings = [
        'message-seen-welcome-imported',
        'message-seen-cookies',
    ];

    protected $appends = [
        'username',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'whitelist_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'email_verified', 'email_verified_token', 'email_verified_at',
        'birth_date', 'country', 'rules_seen_at', 'ipb_token', 'ipb_refresh', 'disabled',
        'disabled_reason', 'email_enabled', 'disabled_at',
    ];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'remember_token',
    ];

    public function exams()
    {
        return $this->hasMany(\App\Exam::class);
    }

    public function interviewing()
    {
        return $this->hasMany(\App\Exam::class, 'interview_user_id');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Review::class);
    }

    public function names()
    {
        return $this->hasMany(\App\Name::class);
    }

    public function player()
    {
        return $this->belongsTo(\App\Arma\Player::class, 'steamid', 'pid');
    }

    /**
     * Obtener la GUID del usuario
     * https://gist.github.com/Fank/11127158.
     *
     * @return string
     */
    public function getGuidAttribute($value)
    {
        if (is_null($value)) {
            $steamID = $this->steamid;
            $temp = '';
            for ($i = 0; $i < 8; ++$i) {
                $temp .= chr($steamID & 0xFF);
                $steamID >>= 8;
            }
            $guid = md5('BE'.$temp);
            $this->attributes['guid'] = $guid;
            $this->timestamps = false;
            $this->save();
            $this->timestamps = true;
        }

        return $this->attributes['guid'];
    }

    public function getEmailVerifiedTokenAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['email_verified_token'] = Str::random(32);
            $this->timestamps = false;
            $this->save();
            $this->timestamps = true;
        }

        return $this->attributes['email_verified_token'];
    }

    public function getUsernameAttribute()
    {
        return Cache::remember('users.'.$this->id.'.attributes.username', 15, function () {
            if (! is_null($this->name)) {
                return $this->name;
            }
            if ($this->names()->active()->count() > 0) {
                return $this->names()->active()->first()->name;
            }

            return $this->steamid;
        });
    }

    public function getTimezoneAttribute($value)
    {
        return Cache::remember('user.'.$this->id.'.attributes.timezone', 15, function () use ($value) {
            if (is_null($value)) {
                return config('app.timezone');
            }

            return $this->attributes['timezone'];
        });
    }

    public function getActiveName()
    {
        if (! is_null($this->name)) {
            return $this->name;
        }
        if ($this->names()->active()->count() > 0) {
            return $this->names()->active()->first()->name;
        }

        return null;
    }

    public function hasFinishedSetup()
    {
//        if($this->has_game == 0) {
//            return false;
//        }
//        if(is_null($this->country)) {
//            return false;
//        }
//        if(is_null($this->birth_date)) {
//            return false;
//        }
//        if(is_null($this->email_enabled)) {
//            return false;
//        }
//        if(is_null($this->rules_seen_at)) {
//            return false;
//        }
//        if($this->names()->where('invalid', 0)->where('end_at', '>', Carbon::now())->count() > 0 || $this->names()->where('needs_review', true)->count() > 0) {
//            return false;
//        }
//        if(is_null($this->rules_seen_at)) {
//            return false;
//        }
//        if(!$this->imported_exam_exempt && $this->exams()->where('passed', true)->count() == 0) {
//            return false;
//        }
//        if(is_null($this->ipb_id)) {
//            return false;
//        }
//        if(!$this->imported_exam_exempt && $this->exams()->where('passed', true)->where('interview_passed', true)->count() == 0) {
//            return false;
//        }
//        return true;
        return 9999 == $this->getSetupStep();
    }

    public function getSetupStep()
    {
        return Cache::remember('user.'.$this->id.'.getSetupStep', 15, function () {
            // 0 comprobar juego
            if (! $this->has_game) {
                return 0;
            }
            // 1 datos
            if (is_null($this->birth_date) || is_null($this->country) || is_null($this->attributes['timezone'])) {
                return 1;
            }
            // 2 email
            if (is_null($this->email_enabled)) {
                return 2;
            } else {
                if ($this->email_enabled && ! $this->email_verified) {
                    return 2;
                }
            }
            // 3 name
            if (0 == $this->names()->active()->count() && 0 == $this->names()->where('needs_review', true)->count()) {
                return 3;
            }
            // 4 rules
            if (! $this->imported_exam_exempt && (is_null($this->rules_seen_at) || $this->rules_seen_at->addMinutes(30) >= Carbon::now())) {
                return 4;
            }
            // 5 exam
            if (! $this->imported_exam_exempt && 0 == $this->exams()->where('passed', true)->where(function ($query) {
                return $query->whereNull('interview_passed')->orWhere('interview_passed', true);
            })->count()) {
                return 5;
            }
            // 6 forum
            if (! config('pcu.forum_skip') && is_null($this->ipb_id)) {
                return 6;
            }
            // 7 interview
            if (! $this->imported_exam_exempt && 0 == $this->exams()->where('passed', true)->where('interview_passed', true)->count()) {
                return 7;
            }

            return 9999;
        });
    }

    public function getSetupSteps()
    {
        return 7;
    }

    public function isDisabled()
    {
        return $this->disabled;
    }

    public function isAdmin()
    {
        return $this->admin;
    }

    public function getOngoingExam()
    {
        return $this->exams()->whereNull('passed')->where('finished', false)->where('end_at', '>', Carbon::now())->orderByDesc('created_at')->first();
    }

    /**
     * Calcula el DNI completo con letra de control
     * https://archive.is/EIw9H.
     *
     * @return bool|string
     */
    public function getDniAttribute()
    {
        $numbers = substr($this->steamid, -8);
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

    public function hasExamCooldown($date = false)
    {
        $exams = $this->exams();
        if (0 == $exams->count()) {
            return false;
        }
        if (1 == $exams->where('passed', 0)->count()) {
            $exam = $exams->where('passed', 0)->latest('passed_at')->first();
            $date = $exam->passed_at->addDays(1);
            if ($date > Carbon::now()) {
                if ($date) {
                    return $date;
                } else {
                    return true;
                }
            }

            return false;
        }
        if (2 == $exams->where('passed', 0)->count()) {
            $exam = $exams->where('passed', 0)->latest('passed_at')->first();
            $date = $exam->passed_at->addDays(2);
            if ($date > Carbon::now()) {
                if ($date) {
                    return $date;
                } else {
                    return true;
                }
            }

            return false;
        }
        if ($exams->where('passed', 0)->count() >= 3) {
            $exam = $exams->where('passed', 0)->latest('passed_at')->first();
            $date = $exam->passed_at->addDays(30);
            if ($date > Carbon::now()) {
                if ($date) {
                    return $date;
                } else {
                    return true;
                }
            }

            return false;
        }

        return false;
    }

    public function getExamTriesRemaining()
    {
        $count = $this->exams->count();
        if ((3 - ($count)) <= 0) {
            return 0;
        }

        return 3 - $count;
    }

    public function getInterviewExam()
    {
        return $this->exams()
            ->where('passed', true)
            ->whereNull('interview_passed')
            ->latest()->first();
    }

    public function hasInterviewOngoing()
    {
        return $this->exams()
            ->where('passed', true)
            ->whereNull('interview_passed')
            ->whereNotNull('interview_at')
            ->whereNotNull('interview_user_id')
            ->count() > 0;
    }

    public function isInterviewing($model = false)
    {
        if ($this->interviewing()->whereNull('passed')->count() > 0) {
            if ($model) {
                return $this->interviewing()->whereNull('passed')->latest()->first();
            }

            return true;
        }

        return false;
    }

    public function canEnableEmail()
    {
        // Si el usuario no ha verificado su cuenta anteriormente
        if ($this->email_prevent) {
            return false;
        }
        if ($this->getSetupStep() < 3) {
            return false;
        }
        if (is_null($this->email_disabled_at)) {
            return true;
        }

        return $this->email_disabled_at->addMinutes(15) <= Carbon::now();
    }

    /**
     * Determina si un usuario debería estar viendo las normas.
     *
     * @return bool puede el usuario o no ver las normas
     */
    public function canSeeRules()
    {
        // Si ha terminado, puede
        if ($this->hasFinishedSetup()) {
            return true;
        }
        $step = $this->getSetupStep();

        // Si está en el paso de las normas
        if (4 == $step) {
            return true;
        }
        // Si está en el paso de examen pero no ha empezado
        if (5 == $step) {
            return is_null($this->getOngoingExam());
        }
        // Si le están entrevistando
        if (7 == $step) {
            return ! $this->hasInterviewOngoing();
        }
        // Todo lo demás, no.
        return false;
    }
}
