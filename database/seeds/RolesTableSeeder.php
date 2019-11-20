<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(array(
            array(
                'name' => 'admin'
            ),
            array(
                'name' => 'mentor'
            ),
            array(
                'name' => 'coordinator'
            ),
            array(
                'name' => 'student'
            ),
            array(
                'name' => 'sponsor'
            ),
        ));
    }
}
