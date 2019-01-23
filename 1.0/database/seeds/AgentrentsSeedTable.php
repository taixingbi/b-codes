<?php

use Illuminate\Database\Seeder;

class AgentrentsSeedTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agentrents = new \App\Agentrent([
            'title' => '1 hour',
            'adult' => 15,
            'child' => 12,
            'tandem' => 30,
            'road' => 30,
            'mountain' => 30,
            'trailer' => 12,
            'seat' => 5,
            'dropoff' => 5,
            'insurance' => 2
        ]);
        $agentrents->save();
    }
}
