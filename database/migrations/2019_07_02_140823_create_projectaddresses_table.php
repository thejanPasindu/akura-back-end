<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectaddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('projectaddresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            

            
            $table->BigInteger('project_id')->unsigned();

            $table->string('province');
            $table->string('city');
            $table->string('zip')->nullable();
            $table->string('addres_line_1');
            $table->string('addres_line_2')->nullable();

            $table->timestamps();
            //$table->engine = 'InnoDB';
            $table->foreign('project_id')->references('id')->on('projects');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projectaddresses');
    }
}
