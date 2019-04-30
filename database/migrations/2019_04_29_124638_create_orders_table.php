<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->unsignedInteger('outsource_id')->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->unsignedInteger('paper_id')->nullable();
            $table->boolean('is_color')->default(0);
            $table->boolean('is_non_color')->default(0);
            $table->unsignedInteger('quantity')->nullable();
            $table->tinyInteger('pay_type')->nullable();
            $table->double('amount')->default(0);
            $table->double('prepayment')->default(0);
            $table->integer('price_design')->default(0);
            $table->text('comment')->nullable();
            $table->boolean('is_files')->default(0);
            $table->timestamp('date_end')->nullable();
            $table->timestamps();

            $table->index('client_id');
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
}
