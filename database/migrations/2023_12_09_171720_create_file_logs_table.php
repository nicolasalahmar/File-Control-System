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
        Schema::create('file_logs', function (Blueprint $table) {
            $table->id();
            $table->datetime("upload_date")->nullable();
            $table->datetime("check_in_date")->nullable();
            $table->datetime("modify_date")->nullable();
            $table->datetime("check_out_date")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
        });

        Schema::table('file_logs', function($table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_logs');
    }
};
