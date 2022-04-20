<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('lesson_id');
            $table->timestamp('started_at');
            $table->string('lesson_link')->nullable();
            $table->string('record_link')->nullable();

            $table->unique(['group_id', 'lesson_id']);

            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');

            $table->foreign('lesson_id')
                ->references('id')
                ->on('lessons')
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
        Schema::table('groups_lessons', function (Blueprint $table) {
            $table->dropUnique(['group_id', 'lesson_id']);
            $table->dropForeign(['group_id']);
            $table->dropForeign(['lesson_id']);
        });

        Schema::dropIfExists('groups_lessons');
    }
}
