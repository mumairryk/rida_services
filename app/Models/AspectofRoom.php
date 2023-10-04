<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AspectofRoom extends Model
{
    use HasFactory;
    protected $table = "aspect_of_room";
    public $timestamps = false;
    protected $guarded;
}
