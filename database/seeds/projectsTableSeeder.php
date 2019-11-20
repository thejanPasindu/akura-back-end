<?php

use Illuminate\Database\Seeder;

class projectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_types')->insert(array(
            array(
                'type' => 'Disaster Relif'
            ),
            array(
                'type' => 'Workshop'
            ),
            array(
                'type' => 'Seminar'
            ),
            array(
                'type' => 'Donation'
            ),
        ));
    }
}
