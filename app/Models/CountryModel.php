<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    use HasFactory;
    protected $table = "country";
    public $timestamps = false;

    public $fillable = [
        'name',
        'prefix',
        'active',
        'deleted',
        'created_at',
        'updated_at',
        'dial_code',
    ];
    
}
