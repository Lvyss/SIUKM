<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ukm_id')->constrained('ukms')->onDelete('cascade');
            $table->text('motivation');
            $table->text('experience')->nullable();
            $table->text('skills')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'ukm_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('registrations');
    }
};