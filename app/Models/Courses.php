<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Products::class, 'course_product', 'course_id', 'product_id');
    }
}
