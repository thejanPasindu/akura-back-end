<?php

use Illuminate\Database\Seeder;

class DistrictsTableSeeder extends Seeder
{
    /** 
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->insert(array(
            array(
                'district' => 'Galle' 
            ),
            array(
                'district' => 'Matara' 
            ),
            array(
                'district' => 'Hambantota' 
            ),

            array(
                'district' => 'Colombo' 
            ),
            array(
                'district' => 'Gampaha' 
            ),
            array(
                'district' => 'Kalutara' 
            ),

            array(
                'district' => 'Kegalle' 
            ),
            array(
                'district' => 'Rathnapura' 
            ),

            array(
                'district' => 'Kandy' 
            ),
            array(
                'district' => 'Nuwaraeliya' 
            ),
            array(
                'district' => 'Mathale' 
            ),

            array(
                'district' => 'Ampara' 
            ),
            array(
                'district' => 'Trincomalee' 
            ),
            array(
                'district' => 'Batticaloa' 
            ),

            array(
                'district' => 'Badulla' 
            ),
            array(
                'district' => 'Monaragala' 
            ),

            array(
                'district' => 'Kurunegala' 
            ),
            array(
                'district' => 'Puttalam' 
            ),

            array(
                'district' => 'Anuradhapura' 
            ),
            array(
                'district' => 'Polonnaruwa' 
            ),

            array(
                'district' => 'Jaffna' 
            ),
            array(
                'district' => 'Kilinochchi' 
            ),
            array(
                'district' => 'Manar' 
            ),
            array(
                'district' => 'Mulathivu' 
            ),
            array(
                'district' => 'Vavuniya' 
            ),

          ));
    }
}
