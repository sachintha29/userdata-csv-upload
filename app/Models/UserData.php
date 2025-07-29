<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $fillable= ['name', 'email', 'contact_number', 'address', 'birthday'];


    protected $casts = [
    'birthday' => 'date',
];
}
