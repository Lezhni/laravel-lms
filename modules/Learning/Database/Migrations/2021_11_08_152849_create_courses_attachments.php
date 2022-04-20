<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('type', 50);
            $table->string('link')->index();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');
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
            $table->dropIndex(['name']);
            $table->dropIndex(['link']);
            $table->dropForeign(['course_id']);
        });

        Schema::dropIfExists('courses_attachments');
    }
}
