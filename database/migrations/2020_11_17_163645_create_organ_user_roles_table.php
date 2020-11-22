<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organ_user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organ_user_id');
            $table->foreign('organ_user_id')->references('id')->on((new \App\OrganUser())->getTable());
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on((new \App\Role())->getTable());
            $table->string('role_name', 64);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->unique(['organ_user_id', 'role_id'], 'organ_user_role_unq');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organ_user_roles');
    }
}
