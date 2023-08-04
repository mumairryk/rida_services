<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypes extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function breads(){
        return $this->hasMany(Breeds::class, 'room_type_id');
    }
}
