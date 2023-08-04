<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groomers extends Model
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
    public function groomer_dates()
    {
        return $this->hasMany(GroomerCalender::class, 'groomer_id');
    }
    public function service_quotes()
    {
        return $this->hasMany(ServiceQuotes::class, 'groomer_id');
    }
}
