<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SquareFootage extends Model
{
    use HasFactory;
    protected $table = "square_footage";
    public $timestamps = false;
    protected $guarded;
}
