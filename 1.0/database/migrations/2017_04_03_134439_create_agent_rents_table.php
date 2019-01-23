<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_rents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->float('adult');
            $table->float('child');
            $table->float('tandem');
            $table->float('road');
            $table->float('mountain');
            $table->float('trailer');
            $table->float('seat');
            $table->float('dropoff');
            $table->float('insurance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_rents');
    }
}
