<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_id');
            $table->integer('question_id');
            $table->text('question_text');
            $table->text('answer')->nullable();
            $table->text('answer_id')->nullable();
            $table->text('user_problem_message')->nullable();
            $table->integer('score')->nullable();
            // Supervisor
            $table->boolean('needs_supervisor')->default(false);
            $table->string('needs_supervisor_reason')->nullable();
            $table->dateTime('supervisor_at')->nullable();
            $table->string('supervisor_action')->nullable();
            $table->integer('supervisor_id')->nullable();
            // Timestamps y demás
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
