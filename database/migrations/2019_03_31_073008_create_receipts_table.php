<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('order_id')->nullable();

            $table->unsignedBigInteger('price');

            $table->enum(
                'status', \App\Enums\ReceiptStatusEnum::values()
            )->default(\App\Enums\ReceiptStatusEnum::UnPaid);

            $table->string('image')->nullable();

            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
