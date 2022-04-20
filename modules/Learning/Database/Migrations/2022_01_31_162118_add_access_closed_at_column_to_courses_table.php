<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccessClosedAtColumnToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->timestamp('access_closed_at')->nullable()->after('finished_at');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->timestamp('access_closed_at')->nullable()->after('finished_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('access_closed_at');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('access_closed_at');
        });
    }
}
