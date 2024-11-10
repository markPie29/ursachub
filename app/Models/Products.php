<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Courses;


class Products extends Model
{
    use HasFactory;

    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'course_product', 'product_id', 'course_id');
    }
}
