<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePostAuthorid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('posts', function (Blueprint $table) {
            $table->bigInteger('author_id')->unsigned()->change();
        });
//        Schema::table('posts', function (Blueprint $table) {
//            $table->foreign('author_id')
//                ->references('id')->on('users')->onDelete('cascade');
//        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('posts', function (Blueprint $table) {
            //
            $table->integer('author_id')->change();
            // $table->dropForeign(['author_id']);
        });

    }
}
