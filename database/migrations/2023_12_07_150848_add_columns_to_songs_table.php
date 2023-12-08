<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->string('album')->nullable();
            $table->string('generous')->nullable();
            $table->string('artist')->nullable();
            $table->integer('favorite')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('album');
            $table->dropColumn('generous');
            $table->dropColumn('artist');
            $table->dropColumn('favorite');
        });
    }
};
