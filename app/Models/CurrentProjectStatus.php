<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentProjectStatus extends Model
{
    use HasFactory;
    protected $table = "current_project_status";
    public $timestamps = false;
    protected $guarded;
}
