<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('org_id')->nullable()->unsigned();
            $table->integer('loc_id')->nullable()->unsigned();
            //
        });

        // Create table for Organizations
        Schema::create('organizations', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('name');
            $table->string('type');
            $table->integer('phonecode')->nullable();
            $table->string('deliverymodel_id')->nullable();
            $table->string('deliverymodel_id_friendlyname')->nullable();
            $table->integer('comsrv_id')->nullable();
            $table->integer('mgrsrv_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->timestamps();
        });


        // Create table for Organizations
        Schema::create('locations', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('name');
            $table->integer('org_id');
            $table->integer('phonecode')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('is_active')->default('1');
            $table->tinyInteger('is_localized')->default('0');
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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(['org_id','loc_id']);
            Schema::dropIfExists('organizations');
            Schema::dropIfExists('locations');
        });
    }
}
