<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntityTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game', function (Blueprint $table) {
            $table->binary('id');
            $table->string('name');
            $table->string('publisher');
            $table->integer('release_date');
            $table->string('encryption_key');
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('user', function (Blueprint $table) {
            $table->binary('id');
            $table->string('name');
            $table->string('email');
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('comment', function (Blueprint $table) {
            $table->binary('id');
            $table->binary('user_id');
            $table->binary('game_id');
            $table->string('comment');
            $table->integer('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game');
        Schema::drop('user');
        Schema::drop('comment');
    }
}
