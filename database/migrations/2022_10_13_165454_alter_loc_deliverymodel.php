<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLocDeliverymodel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('locations', function (Blueprint $table) {
            $table->string('deliverymodel_id')->nullable()->change();
            $table->string('deliverymodel_id_friendlyname')->nullable()->change();
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
        Schema::table('locations', function (Blueprint $table) {
            $table->string('deliverymodel_id')->nullable(false)->change();
            $table->string('deliverymodel_id_friendlyname')->nullable(false)->change();
        });


    }
}
