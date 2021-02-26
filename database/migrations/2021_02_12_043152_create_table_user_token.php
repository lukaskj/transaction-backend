<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_token', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->uuid('user_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('token', 255)->unique();
            $table->dateTime('expire_date')->nullable(false);
            $table->string('user_agent')->nullable();
            $table->string('device', 50)->nullable()->default('web');
            $table->string('ip')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // $table->dropSoftDeletes();
        Schema::dropIfExists('user_token');
    }
}
