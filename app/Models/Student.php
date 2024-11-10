<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    // Make sure to fillable the 'course_id' and other required fields
    protected $fillable = ['first_name', 'last_name', 'middle_name', 'student_id', 'course_id', 'password'];

    protected $hidden = ['password', 'remember_token'];

    // Define the relationship with the Course model
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id'); // Ensure correct foreign key 'course_id'
    }
}

