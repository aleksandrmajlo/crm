<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderlogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id');

            $table->bigInteger('status');
            $table->bigInteger('failedstatus')->nullable();
            $table->text('comment')->nullable();
            $table->text('commentadmin')->nullable();
            $table->tinyInteger('showcommentadmin')->default(0);

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
        Schema::dropIfExists('orderlogs');
    }
}
