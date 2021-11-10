<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('docs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->comment('Название');
            $table->longText('text')->comment('Содержимое файла');
            $table->integer('user_id')->nullable()->comment('кем загружен файл');
            $table->integer('admin_id')->comment('кем загружен файл');

            $table->integer('status')->default(1)->comment('1-  в работе, 2 - завершена ');

            $this->timestamp('start')->nullable();
            $this->timestamp('end')->nullable();

            $table->timestamps();

        });

        /*
        Schema::create('doc_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('doc_id')->index()->comment('кем загружен файл');
            $table->integer('user_id')->index()->comment('кем загружен файл');
            $table->integer('status')->default(1)->comment('1-  в работе, 2 - завершена ');
            $table->timestamps();
        });
        */


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs');
        Schema::dropIfExists('doc_user');
    }
}
