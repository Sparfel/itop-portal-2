<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItopUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for iTop Users Imports
        Schema::create('itop_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itop_id')->nullable();
            $table->integer('portal_id')->nullable();
            $table->string('login')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->integer('org_id');
            $table->string('org_name');
            $table->integer('location_id')->nullable();
            $table->string('location_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->text('comment')->nullable();
            $table->longText('error')->nullable();
            $table->tinyInteger('is_local')->default('0');
            $table->tinyInteger('is_in_itop')->default('0');
            $table->tinyInteger('has_itop_account')->default('0');
            $table->integer('role_id')->nullable();
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
        //
        Schema::dropIfExists('itop_users');
    }
}
