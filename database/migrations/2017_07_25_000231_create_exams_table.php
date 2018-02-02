<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            // Estructura
            $table->text('structure');
            // Inicio y final
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at');
            $table->dateTime('expires_at');
            // Examen finalizado antes de tiempo?
            $table->boolean('finished')->default(false);
            $table->date('finished_at')->nullable();
            // Corrección
            $table->integer('score')->nullable();
            $table->boolean('passed')->nullable();
            $table->boolean('passed_temporal')->nullable();
            $table->dateTime('passed_at')->nullable();
            $table->integer('passed_at_user_id')->nullable();
            $table->text('passed_message')->nullable();
            // Revisión administrativa
            $table->boolean('review_required')->default(false);
            $table->integer('review_user_id')->nullable();
            $table->dateTime('review_at')->nullable();
            // Entrevista
            $table->dateTime('interview_at')->nullable();
            $table->dateTime('interview_end_at')->nullable();
            $table->string('interview_code')->nullable();
            $table->dateTime('interview_code_at')->nullable();
            $table->boolean('interview_passed')->nullable();
            $table->integer('interview_user_id')->nullable();
            $table->string('interview_audio_url')->nullable();
            $table->string('interview_audio_encoded_at')->nullable();
            $table->string('interview_audio_message')->nullable();
            // ... timestamps y eso
            $table->softDeletes();
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
        Schema::dropIfExists('exams');
    }
}
