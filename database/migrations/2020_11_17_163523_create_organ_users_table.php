<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organ_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'organ_user_fk')->references('id')->on((new \App\User())->getTable());
            $table->unsignedBigInteger('organ_id');
            $table->foreign('organ_id', 'user_organ_fk')->references('id')->on((new \App\Organ())->getTable());
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->unique(['user_id','organ_id'], 'user_organ_unq');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organ_users');
    }
}
