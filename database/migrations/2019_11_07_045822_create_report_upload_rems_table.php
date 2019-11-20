<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportUploadRemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_upload_rems', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('student_id');
            $table->integer('grade')->nullable();
            $table->integer('term')->nullable();
            $table->integer('level')->nullable();
            $table->integer('semester')->nullable();

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
        Schema::dropIfExists('report_upload_rems');
    }
}
