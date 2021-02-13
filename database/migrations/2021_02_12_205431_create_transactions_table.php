<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration {
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up() {
      Schema::create('transactions', function (Blueprint $table) {
         $table->id();
         $table->unsignedInteger('transaction_type_id')->nullable(false);
         $table->unsignedBigInteger('amount')->nullable(false)->comment('Integer rather than float due to float round errors.');
         $table->string('description', 500)->nullable()->comment('Transaction description');
         $table->unsignedBigInteger('user_id')->nullable(false)->comment('Transaction main user. On tranfers between users he is the \'from\' user.');
         $table->unsignedBigInteger('user_id_ref')->nullable(true)->comment('Transaction \'to\' user.');
         $table->unsignedBigInteger('transaction_id_ref')->nullable(true)->comment('Transaction reference ID.');
         $table->unsignedTinyInteger('status')->default(1)->comment('Transaction status');

         $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
         $table->foreign('user_id')->references('id')->on('users');
         $table->foreign('user_id_ref')->references('id')->on('users');
         $table->foreign('transaction_id_ref')->references('id')->on('transactions');

         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down() {
      Schema::dropIfExists('transactions');
   }
}
