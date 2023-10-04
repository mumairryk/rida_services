<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeofProperty extends Model
{
    use HasFactory;
    protected $table = "type_of_property";
    public $timestamps = false;
    protected $guarded;
}
