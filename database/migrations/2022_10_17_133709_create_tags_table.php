<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->change();});

        Schema::create('tags', function(Blueprint $table) {
            $table->id();
            $table->string('tag', 50)->unique();
            $table->string('slug')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::table('posts', function (Blueprint $table) {
            $table->Integer('id')->change();});

    }
}
