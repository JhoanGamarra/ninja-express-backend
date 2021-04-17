<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            //$table->string('token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            /*$table->bigInteger('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->bigInteger('business_id')->unsigned()->nullable();
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->bigInteger('courier_id')->unsigned()->nullable();
            $table->foreign('courier_id')->references('id')->on('couriers');*/
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
