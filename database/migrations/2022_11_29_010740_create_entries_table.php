<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('entries', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->date('date')->nullable();
      $table->decimal('amount', 9, 2);
      $table->enum('type', ['SPENDING', 'INCOME']);
      $table->string('description')->nullable();
      $table->foreignId('account_id');
      $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
      $table->foreignId('category_id');
      $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('entries');
  }
};
