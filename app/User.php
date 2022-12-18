<?php

namespace App;

use App\Wallet;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    // protected $fillable = [
    //     'name', 'email', 'password', 'phone', 'ip', 'user_agent', 'login_at'
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id');
    }
}
