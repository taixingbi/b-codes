<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class Agent_tourSeedTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agent_tours')->insert([
            'title' => "public(2h)",
            'adult' => 45,
            'child' => 35,
        ]);

        DB::table('agent_tours')->insert([
            'title' => "private(2h)",
            'adult' => 75,
            'child' => 50,
        ]);

        DB::table('agent_tours')->insert([
            'title' => "private(3h)",
            'adult' => 100,
            'child' => 75,
        ]);
    }
}
