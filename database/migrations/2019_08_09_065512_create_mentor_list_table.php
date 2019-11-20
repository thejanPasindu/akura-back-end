<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMentorListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW mentor_ids AS SELECT User_id FROM user_role WHERE role_id IN ( SELECT id FROM roles WHERE name='mentor')");
        DB::statement("CREATE VIEW mentor_list AS SELECT id, name FROM users WHERE id IN ( SELECT user_id FROM mentor_ids)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW mentor_ids");
        DB::statement("DROP VIEW mentor_list");
    }
}
