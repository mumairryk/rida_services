<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPurpose extends Model
{
    use HasFactory;
    protected $table = "project_purpose";
    public $timestamps = false;
    protected $guarded;
}
