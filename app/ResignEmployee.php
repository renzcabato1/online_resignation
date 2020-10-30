<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResignEmployee extends Model
{
    //

    public function upload_pdf()
    {
        return $this->belongsTo(UploadPdf::class);
    }
    public function clearance_info()
    {
        return $this->hasOne(Clearance::class,'upload_pdf_id','upload_pdf_id');
    }
}
