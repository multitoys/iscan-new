<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sms_id')->nullable();
            $table->unsignedInteger('order_id');
            $table->tinyInteger('type');
            $table->text('message');
            $table->boolean('is_sent')->default(0);
            $table->string('status', 100)->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms');
    }
}