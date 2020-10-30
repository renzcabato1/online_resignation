<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClearanceSignatory extends Model
{
    //

    public function department_info()
    {
        return $this->hasOne(Department::class,'id','department_id');
    }
  
    public function user_info()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
   public function clearance_info()
   {
    return $this->hasOne(Clearance::class,'id','clearance_id');
   }
}
