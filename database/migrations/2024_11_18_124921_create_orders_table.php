<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('size');
            $table->decimal('price', 8, 2);
            $table->string('org');
            $table->integer('quantity');
            $table->string('student_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename')->nullable();
            $table->string('course');
            $table->string('payment_method');
            $table->string('reference_number')->nullable();
            $table->string('order_number');
            $table->string('status')->nullable();
            $table->string('gcash_proof')->nullable(); // Proof of payment photo
            $table->string('claimed_by')->nullable();  // Name of the person who claimed the order
            $table->timestamp('claimed_at')->nullable(); // Date and time the order was claimed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
