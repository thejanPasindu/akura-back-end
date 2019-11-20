<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id');

            $table->string('school')->nullable();
            $table->string('university')->nullable();
            $table->string('faculty')->nullable();
            $table->integer('grade')->nullable();
            $table->integer('academicyear')->nullable();
            $table->integer('semester')->nullable();
            $table->string('addrboarding')->nullable();

            //guardian details
            $table->string('guardian_name'); 
            $table->string('relationship');
            $table->string('guardian_tp');
            $table->string('guardian_address');

            //Family income
            $table->double('family_anual_income', 8, 2);
            $table->double('family_anual_expence', 8, 2);

            //Certificate
            $table->string('birth_certificate_1');
            $table->string('birth_certificate_2');
            $table->string('confirm_letter');
            $table->string('gn_certificate');
            $table->integer('approve');
            $table->integer('reject');
            $table->integer('has_sponsor');

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
        Schema::dropIfExists('scholarships');
    }
}
