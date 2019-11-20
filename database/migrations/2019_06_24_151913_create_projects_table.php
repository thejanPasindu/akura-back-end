<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->integer('creater_id');

            $table->string('project_name');
            $table->integer('project_manager_id');
            $table->integer('project_coordinator1')->default(null);
            $table->integer('project_coordinator2')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location');
            $table->integer('district');
            $table->string('city');
            $table->integer('zip')->nullable();
            $table->string('addres_line_1');
            $table->string('addres_line_2')->nullable();
            $table->string('type');
            $table->text('description');    

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
