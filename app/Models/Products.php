<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['org', 'name', 'size', 'stocks', 'price', 'photos'];

    // Cast 'photos' as an array to automatically convert JSON to an array when retrieving it
    protected $casts = [
        'photos' => 'array',
    ];
}
