<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignHead extends Model
{
    //
    protected $connection = 'hr_portal';

    public function employee_info()
    {
        return $this->hasOne(Employee::class,'id','employee_id');
    }
   
    public function approver()
    {
        return $this->belongsTo(Employee::class,'employee_head_id','id')->select('id','name');
    }
    public function approver_info()
    {
        return $this->belongsTo(Employee::class,'employee_head_id','id');
    }
    public function approver_of_approver()
    {
        return $this->hasOne(AssignHead::class,'employee_id','employee_head_id')->where('head_id',3)->select('employee_id','employee_head_id','head_id');
    }
    public function under_info()
    {
        return $this->hasMany(AssignHead::class,'employee_head_id','employee_id')->where('head_id',3)->select('employee_id','employee_head_id','head_id');
    }
}
