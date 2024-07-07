<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleIdToMaintenanceActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('maintenance_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('schedule_id')->after('maintenance_id');
            $table->foreign('schedule_id')->references('id')->on('maintenance_schedules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('maintenance_activities', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');
        });
    }
}
