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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('invoice_id');
      $table->unsignedBigInteger('product_id');
      $table->integer('qty');
      $table->bigInteger('price');

      $table->timestamps();

      // * Relationship to invoices
      $table->foreign('invoice_id')
        ->references('id')
        ->on('invoices');

      // * Relationship to products
      $table->foreign('product_id')
        ->references('id')
        ->on('products');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('orders');
  }
};
