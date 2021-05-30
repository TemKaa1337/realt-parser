<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertismentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisments', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->string('header');
            $table->integer('byn_price')->nullable();
            $table->integer('usd_price')->nullable();
            $table->json('phones');
            $table->json('emails');
            $table->string('location');
            $table->integer('room_count');
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->date('posted_at');
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
        Schema::dropIfExists('advertisments');
    }
}
