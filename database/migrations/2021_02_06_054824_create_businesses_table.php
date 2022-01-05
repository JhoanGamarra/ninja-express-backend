<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->boolean('available')->default(1);
            $table->string('name')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();  
            $table->foreign('category_id')->references('id')->on('categories');
            $table->bigInteger('address_id')->unsigned()->nullable();  
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->bigInteger('user_id')->unsigned();  
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('businesses');
    }
}
