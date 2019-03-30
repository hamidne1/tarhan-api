<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffOptionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariff_options', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('tariff_id');

            $table->string('title');
            $table->string('icon');
            $table->enum('type', \App\Enums\OptionTypeEnum::values());

            $table->foreign('tariff_id')
                ->references('id')->on('tariffs')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariff_options');
    }
}
