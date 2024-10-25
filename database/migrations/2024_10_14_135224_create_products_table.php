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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('org'); // Organization name or ID (could also reference an Orgs table)
            $table->string('name'); // Product name
            $table->integer('small'); 
            $table->integer('medium'); 
            $table->integer('large'); 
            $table->integer('extralarge'); 
            $table->integer('double_extralarge'); 
            $table->decimal('price', 8, 2); // Product price with 2 decimal points
            $table->json('photos')->nullable(); // Store up to 5 photos as a JSON array
            $table->timestamps(); // Automatically includes 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
