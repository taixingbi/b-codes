<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosRentsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_rents_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('agent_email');
            $table->boolean('order_completed');
            $table->string('payment_type');
            $table->float('total_price_after_tax');
            $table->dateTime('created_at');
            $table->string('date');
            $table->string('time');
            $table->integer('adult');
            $table->integer('child');
            $table->integer('tandem');
            $table->integer('road');
            $table->integer('mountain');
            $table->integer('trailer');
            $table->integer('seat');
            $table->boolean('dropoff');
            $table->boolean('insurance');
            $table->string('duration');
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
        Schema::dropIfExists('pos_rents_orders');
    }
}
