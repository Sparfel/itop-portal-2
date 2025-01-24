<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrgDeliverymodel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Le modèle de support n'est plus géré au niveau de l'organisation
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('deliverymodel_id')->nullable()->change();
            $table->string('deliverymodel_id_friendlyname')->nullable()->change();
        });
        // il est défini désormais au niveau des sites
        Schema::table('locations', function (Blueprint $table) {
            $table->string('deliverymodel_id');
            $table->string('deliverymodel_id_friendlyname');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('deliverymodel_id')->nullable(false)->change();
            $table->string('deliverymodel_id_friendlyname')->nullable(false)->change();
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['deliverymodel_id','deliverymodel_id_friendlyname']);
        });
    }
}
