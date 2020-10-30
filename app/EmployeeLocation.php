<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeLocation extends Model
{
    //
    protected $connection = 'hr_portal';
    
    public function locationName()
    {
        return $this->hasOne(Location::class,'id','location_id');
    }
}
