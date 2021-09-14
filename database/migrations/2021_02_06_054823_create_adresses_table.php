<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('state');
            $table->string('city');
            $table->string('address');
            $table->string('country')->nullable();
            $table->string('lat');
            $table->string('lng');
            $table->string('description');
            $table->bigInteger('client_id')->unsigned()->nullable();  
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('CASCADE');
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
        Schema::dropIfExists('addresses');
    }
}
