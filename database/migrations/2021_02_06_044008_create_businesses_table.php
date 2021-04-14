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
            $table->string('name')->unique()->nullable();
            $table->string('email')->unique();
            $table->mediumText('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();;  
            $table->foreign('category_id')->references('id')->on('categories');
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
