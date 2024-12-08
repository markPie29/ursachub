<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Courses;

class Student extends Authenticatable
{
    use Notifiable;

    // Make sure to fillable the 'course_id' and other required fields
    protected $fillable = ['first_name', 'last_name', 'middle_name', 'student_id', 'course_id', 'password'];

    protected $hidden = ['password', 'remember_token'];

    // Define the relationship with the Course model
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id'); // Ensure correct foreign key 'course_id'
    }
    
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }
}

