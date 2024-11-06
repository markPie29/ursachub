<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['first_name', 'last_name', 'middle_name', 'student_id', 'course', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
