<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'student_id', 'firstname', 'lastname', 
        'middlename', 'course', 'payment_method', 'reference_number'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
