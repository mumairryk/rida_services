<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceQuotes extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function pets(){
    //     return $this->belongsTo('App\Models\MyPets', 'pet_id', 'id');
    // }

    public function appointment_types()
    {
        return $this->belongsTo('App\Models\AppointmentTypes', 'appointment_type', 'id');
    }

    public function feeding_schedules()
    {
        return $this->belongsTo('App\Models\FeedingSchedules', 'feeding_schedule', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\Models\Doctors', 'doctor_id', 'id');
    }

    public function groomer()
    {
        return $this->belongsTo('App\Models\Groomers', 'groomer_id', 'id');
    }
    public function play_staff()
    {
        return $this->belongsTo('App\Models\PlaytimeStaffs', 'playtime_staff_id', 'id');
    }
    public function grooming_type()
    {
        return $this->belongsTo('App\Models\GroomingTypes', 'grooming_service', 'id');
    }
    // public function food_det(){
    //     return $this->belongsTo('App\Models\Foods', 'food_id', 'id');
    // }
    public function pets()
    {
        return $this->hasMany(ServicePets::class, 'service_id', 'id');
    }

    public function foods()
    {
        return $this->hasMany(ServiceFoods::class, 'service_id', 'id');
    }
    public function room()
    {
        return $this->hasOne(RoomTypes::class, 'id', 'room_id');
    }
    public function getQuoteDocumentAttribute($value)
    {
        if ($value) {
            return get_uploaded_image_url($value, 'service_image_upload_dir');
            return asset($value);
        } else {
            return '';
        }
    }

}
