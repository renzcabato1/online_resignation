<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    protected $connection = 'hr_portal';
    use Notifiable;

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
    public static function role(){
        return Account::where('user_id', Auth::user()->id)
            ->get([
                'role_id',
            ])->first();
    }

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
    public function employee_info()
    {
        return Employee::where('user_id', Auth::user()->id)
            ->first();
        
    }

   
   
}
