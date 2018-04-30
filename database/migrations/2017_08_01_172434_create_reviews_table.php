<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reviewable_type');
            $table->integer('reviewable_id');
            $table->string('type')->nullable();
            $table->integer('score')->nullable();
            $table->boolean('supervisor')->default(false);
            $table->integer('user_id');
            $table->text('message')->nullable();
            $table->boolean('abuse')->default(false);
            $table->text('abuse_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
