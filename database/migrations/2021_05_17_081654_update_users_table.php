<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('itop_id')->unique()->nullable()->unsigned();
            $table->integer('itop_cfg')->unsigned()->nullable(false)->default(1);
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->integer('postal_code')->nullable()->unsigned();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('department')->nullable();
            $table->string('service')->nullable();
            $table->string('office_id')->nullable();
            $table->string('office_id_2')->nullable();
            $table->string('internal_phone')->nullable();
            $table->string('internal_phone_2')->nullable();
            $table->string('dect_phone')->nullable();
            $table->integer('cellphone_id')->nullable()->unsigned();
            $table->string('pc1')->nullable();
            $table->string('pc2')->nullable();
            $table->string('pc3')->nullable();
            $table->tinyInteger('is_active')->default('1');
            $table->tinyInteger('is_staff')->default('0');
            $table->tinyInteger('is_multi_organization')->default('0');
            $table->tinyInteger('is_localized')->default('0');
            $table->tinyInteger('is_address_visible')->default('0');

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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name','last_name','gender','itop_id', 'itop_cfg',
                'is_staff','is_multi_organization','is_active','address','postal_code',
                'city','country','latitude','longitude','is_localized',
                'is_address_visible','department','service','office_id','office_id_2',
                'internal_phone','internal_phone_2','dect_phone','cellphone_id','pc1',
                'pc2','pc3']);
        });
    }
}
