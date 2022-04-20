<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->index();
            $table->unsignedBigInteger('attachment_id')->index();

            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');

            $table->foreign('attachment_id')
                ->references('id')
                ->on('courses_attachments')
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
        Schema::table('groups_attachments', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['attachment_id']);
        });

        Schema::dropIfExists('groups_attachments');
    }
}
