<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('page_id')->nullable();
            $table->string('col');
            $table->enum('group', \App\Enums\ContentGroupEnum::values());
            $table->string('slug')->unique();
            $table->string('alt');
            $table->string('href');
            $table->string('src');

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('set null')->onUpdate('cascade');

            $table->foreign('page_id')
                ->references('id')->on('pages')
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
        Schema::dropIfExists('widgets');
    }
}
