<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class UserPrivileges extends Model
{
    use HasFactory;
    protected $table = "user_privileges";

    public $fillable = [
        'id',
        'user_id',
        'designation_id',
        'privileges',
        'status',
        'created_at',
        'updated_at'
    ];
    public static function privilege(){
        $data = UserPrivileges::join('users', 'users.id', 'user_privileges.user_id')
         ->join('admin_designation', 'admin_designation.id', '=', 'users.designation_id')->where(['users.id' => Auth::user()->id, 'user_privileges.designation_id' => \App\Models\User::where('id', Auth::user()->id)->pluck('designation_id')->first()])->pluck('privileges')->first();
        return $data;
    }
}
