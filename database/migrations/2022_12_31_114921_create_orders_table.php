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
            $table->unsignedBigInteger('user_id');
            $table->text('invoice');
            $table->integer('total');
            $table->text('snap_token');
            $table->string('transaction_status');
            $table->string('order_status');
            $table->text('first_name');
            $table->text('last_name');
            $table->text('street');
            $table->text('detailstreet')->nullable();
            $table->text('city');
            $table->text('postcode');
            $table->text('phone');
            $table->text('email');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
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
