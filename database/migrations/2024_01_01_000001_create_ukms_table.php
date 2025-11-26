<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ukms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('logo')->nullable();
            $table->foreignId('category_id')->constrained('ukm_categories')->onDelete('cascade');
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('instagram')->nullable();
            $table->string('email_ukm')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ukms');
    }
};