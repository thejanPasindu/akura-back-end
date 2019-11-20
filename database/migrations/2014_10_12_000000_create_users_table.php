<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('active')->default(false);
            $table->string('activation_token');

            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('cnumber');
            $table->string('no')->nullable();
            $table->string('street')->nullable();
            $table->string('city');
            $table->string('dob');
            $table->string('gender');
            $table->string('position');
            $table->string('occupation')->nullable();
            $table->string('registrationType')->nullable();
            $table->string('studingYear')->nullable();
            $table->string('studingSemester')->nullable();
            $table->string('paymentType')->nullable();
            $table->double('paymentAmount', 8, 2)->nullable();
            $table->string('medium')->nullable();
            $table->string('subject')->nullable();
//*/
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
