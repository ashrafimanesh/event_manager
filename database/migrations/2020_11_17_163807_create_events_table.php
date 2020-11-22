<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', array_keys(\App\EventModel::allTypes()))->default(\App\EventModel::TYPE_MEETING);
            $table->smallInteger('recurring_intervals')->default(1);
            $table->unsignedBigInteger('organ_id');
            $table->foreign('organ_id')->references('id')->on((new \App\Organ())->getTable());

            $table->enum('payment_type', array_keys(\App\EventModel::allPaymentTypes()))->default(\App\EventModel::PAYMENT_TYPE_MONEY);
            $table->string('payment_amount')->nullable();

            $table->unsignedBigInteger('hold_by')->nullable();

            $table->unsignedBigInteger('start_by')->nullable();

            $table->unsignedBigInteger('expired_by')->nullable();

            $table->timestamp('start_date')->nullable();
            $table->timestamp('expired_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
