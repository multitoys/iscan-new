<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table
                ->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('set null');
            $table
                ->foreign('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('set null');
            $table
                ->foreign('outsource_id')
                ->references('id')
                ->on('outsources')
                ->onDelete('set null');
            $table
                ->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('set null');
            $table
                ->foreign('paper_id')
                ->references('id')
                ->on('papers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['client_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['outsource_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['paper_id']);
        });
    }
}
