<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'content', 'image'];

    protected $casts = [
        'image' => 'array', // Automatically decode JSON to an array
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'original_post_id');
    }
}
