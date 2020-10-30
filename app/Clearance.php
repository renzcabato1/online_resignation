<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    //
    public function clearance_signatories()
    {
        return $this->hasMany(ClearanceSignatory::class);
    }
    public function clearance_signatories_count()
    {
        return $this->hasMany(ClearanceSignatory::class)->where('status','=','1');
    }
    public function upload_pdf_info()
    {
        return $this->hasOne(UploadPdf::class,'id','upload_pdf_id');
    }
}
