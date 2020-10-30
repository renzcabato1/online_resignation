<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $connection = 'hr_portal';


    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}
