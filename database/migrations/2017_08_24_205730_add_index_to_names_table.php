<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToNamesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('names', function (Blueprint $table) {
            $table->index('needs_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('names', function (Blueprint $table) {
            $table->dropIndex('needs_review');
        });
    }
}
