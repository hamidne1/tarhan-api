<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('port', \App\Enums\Gateway\TransactionPortEnum::values());
            $table->unsignedInteger('receipt_id')->nullable();
            $table->decimal('price');
            $table->string('ref_id')->nullable();
            $table->string('tracking_code')->nullable();
            $table->string('ip', 20)->nullable();
            $table->string('card_number', 50)->nullable();
            $table->enum('status', [
                \App\Enums\Gateway\TransactionStatusEnum::values()
            ])->default(\App\Enums\Gateway\TransactionStatusEnum::Init);

            $table->integer('result_code')->nullable();
            $table->string('result_message')->nullable();
            $table->timestamps();
            $table->timestamp('paid_at')->nullable();

            $table->foreign('receipt')
                ->references('id')->on('Receipts')
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
        Schema::dropIfExists('transactions');
    }
}
