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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Product name
            $table->string('org'); // Organization
            $table->string('size'); // Size picked by user
            $table->integer('quantity'); // Quantity
            $table->decimal('price', 8, 2); // Price (original price * quantity)
            $table->json('photos')->nullable(); // Photos (store JSON-encoded paths)
            $table->string('student_id'); // Reference to the student
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
        Schema::dropIfExists('cart');
    }
};
