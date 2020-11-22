<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on((new \App\EventModel())->getTable());
            $table->unsignedBigInteger('organ_user_id');
            $table->foreign('organ_user_id')->references('id')->on((new \App\OrganUser())->getTable());
            $table->enum('status', array_keys(\App\EventParticipant::statuses()));

            $table->unsignedInteger('turn_order')->nullable();

            $table->unsignedBigInteger('active_by')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->timestamp('active_at')->nullable();
            $table->timestamp('turned_at')->nullable();

            $table->unique(['event_id', 'organ_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_participants');
    }
}
