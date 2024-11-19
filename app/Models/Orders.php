<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'size',
        'price',
        'org',
        'quantity',
        'student_id',
        'firstname',
        'lastname',
        'middlename',
        'course',
        'payment_method',
        'reference_number',
        'order_number',
        'status',
    ];

        protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];
}
