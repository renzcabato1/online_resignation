<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class UploadPdf extends Model
{
    //
    use Notifiable;
    public function user_info()
    {
        return $this->hasOne(Employee::class,'user_id','user_id');
    }
    public function cancelled_by()
    {
        return $this->hasOne(User::class,'id','cancel_by');
    }
    public function letter_info()
    {
        return $this->hasMany(Letter::class,'upload_pdf_id','id')->orderBy('id','desc');
    }
    public function acceptance_info()
    {
        return $this->hasOne(ResignEmployee::class);
    }
    public function clearance_info()
    {
        return $this->hasOne(Clearance::class);
    }
    public function approver_id()
    {
        return $this->hasMany(ManageUser::class,'user_id','user_id');
    }
    
}
