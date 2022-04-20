<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryColumnToAttachmentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses_attachments', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('course_id');

            $table->foreign('category_id')
                ->references('id')
                ->on('courses_attachment_categories')
                ->onDelete('set null');
        });

        Schema::table('lessons_attachments', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('lesson_id');

            $table->foreign('category_id')
                ->references('id')
                ->on('lessons_attachment_categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses_attachments', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('lessons_attachments', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
}
