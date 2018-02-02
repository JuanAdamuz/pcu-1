<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            // Básico
            $table->string('name')->unique()->nullable();
            $table->string('steamid')->unique();
            $table->string('guid')->nullable();
            // Email
            $table->string('email')->unique()->nullable();
            $table->boolean('email_enabled')->nullable();
            $table->boolean('email_verified')->default(false);
            $table->string('email_verified_token')->nullable();
            $table->dateTime('email_verified_token_at')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->dateTime('email_disabled_at')->nullable();
            $table->boolean('email_prevent')->default(false);
            // Info
            $table->boolean('has_game')->default(false);
            $table->date('birth_date')->nullable();
            $table->string('country')->nullable();
            $table->string('timezone')->nullable();
            $table->dateTime('rules_seen_at')->nullable();
            // Forum
            $table->string('ipb_token')->nullable();
            $table->string('ipb_refresh')->nullable();
            $table->integer('ipb_id')->nullable();
            // Código de soporte
            $table->string('support_code')->nullable();
            $table->dateTime('support_code_at')->nullable();
            // Import
            $table->dateTime('imported')->nullable();
            $table->boolean('imported_exam_exempt')->default(false);
            $table->text('imported_exam_message')->nullable();
            // Disabled
            $table->boolean('disabled')->default(false);
            $table->string('disabled_reason')->nullable();
            $table->dateTime('disabled_at')->nullable();
            // Cambio de nombre
            $table->integer('name_changes_remaining')->default(0);
            $table->text('name_changes_reason')->nullable();
            // Whitelist
            $table->dateTime('whitelist_at')->nullable();
            // Settings
            $table->text('settings')->nullable();
            // Admin
            $table->boolean('admin')->default(false);
            // Active
            $table->dateTime('active_at')->nullable();
            // Otros
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
