<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortOrderColumnToAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses_attachments', function (Blueprint $table) {
            $table->integer('sort_order')->after('link');
        });

        Schema::table('lessons_attachments', function (Blueprint $table) {
            $table->integer('sort_order')->after('link');
        });

        DB::statement('UPDATE `courses_attachments` SET `sort_order` = `id`');
        DB::statement('UPDATE `lessons_attachments` SET `sort_order` = `id`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses_attachments', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('lessons_attachments', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
}
