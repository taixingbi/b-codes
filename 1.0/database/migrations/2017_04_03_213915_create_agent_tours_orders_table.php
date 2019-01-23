<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentToursOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_tours_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('agent_email');
            $table->boolean('order_completed');
            $table->string('payment_type');
            $table->float('total_price_after_tax');
            $table->string('date');
            $table->string('time');
            $table->integer('adult');
            $table->integer('child');
            $table->integer('total_people');
            $table->string('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_tours_orders');

    }
}
