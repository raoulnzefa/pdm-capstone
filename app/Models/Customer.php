<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\CustomerResetPasswordNotification;

class Customer extends Authenticatable
{
    use Notifiable;

    // protected $guard = 'customer';
    // protected $broker = 'customers';

    // protected $primaryKey = 'number';
    // public $keyType = 'string';
    // public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPasswordNotification($token));
    }

    public function getCreatedAtAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y, H:iA', $time);
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function invoice()
    {
        return $this->hasOne('App\Invoice');
    }

    public function province()
    {
        return $this->belongsTo('App\Province');
    }

    public function municipality()
    {
        return $this->belongsTo('App\Municipality');
    }

    public function barangay()
    {
        return $this->belongsTo('App\Barangay');
    }

    public function getRegisteredAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }
}
