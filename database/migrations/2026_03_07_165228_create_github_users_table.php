<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up() {
    Schema::create('github_users', function (Blueprint $table) {
      $table->id();
      $table->string('provider_id')->unique(); // ID dari GitHub
      $table->string('email')->nullable();
      $table->string('name')->nullable();
      $table->string('nickname')->nullable();
      $table->string('avatar')->nullable();
      $table->json('data')->nullable(); // data mentah dari GitHub
      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('github_users');
  }
};