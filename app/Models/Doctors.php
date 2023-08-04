<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getImageAttribute($value)
    {
        if ($value) {
            return get_uploaded_image_url($value, 'doc_image_upload_dir');
            return asset($value);
        } else {
            return '';
        }
    }
    public function getDocumentAttribute($value)
    {
        if ($value) {
            return get_uploaded_image_url($value, 'doc_image_upload_dir');
            return asset($value);
        } else {
            return '';
        }
    }

    public function doctor_dates()
    {
        return $this->hasMany(DoctorCalender::class, 'doctor_id');
    }
    public function service_quotes()
    {
        return $this->hasMany(ServiceQuotes::class, 'doctor_id');
    }
}
