<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ManageUser extends Model
{
    //
    use Notifiable;

    public function employee_info()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
   
    public function approver()
    {
        return $this->belongsTo(User::class,'approver_id','id')->select('id','name');
    }
    public function approver_info()
    {
        return $this->belongsTo(Employee::class,'approver_id','user_id');
    }
    public function approver_of_approver()
    {
        return $this->hasOne(ManageUser::class,'user_id','approver_id')->select('user_id','approver_id');
    }
    public function under_info()
    {
        return $this->hasMany(ManageUser::class,'approver_id','user_id')->select('user_id','approver_id');
    }
  
}