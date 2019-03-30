<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContextsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contexts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('page_id')->nullable();

            $table->string('slug')->unique();

            $table->string('href')->nullable();
            $table->string('icon')->nullable();
            $table->text('value');

            $table->foreign('parent_id')
                ->references('id')->on('contexts')
                ->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('contexts');
    }
}
