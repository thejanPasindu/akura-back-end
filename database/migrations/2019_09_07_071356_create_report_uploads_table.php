<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportupload', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('student_id');
            $table->integer('acadamic_year')->nullable();
            $table->integer('semester')->nullable();
            $table->integer('school_grade')->nullable();
            $table->integer('term')->nullable();
            $table->string('path');

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
        Schema::dropIfExists('report_uploads');
    }
}
