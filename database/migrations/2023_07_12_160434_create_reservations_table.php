<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned();
            $table->bigInteger('site_building_id')->unsigned();
            $table->bigInteger('site_building_level_id')->unsigned();
            $table->bigInteger('site_building_room_id')->unsigned();
            $table->string('event');
            $table->string('name');
            $table->string('department');
            $table->string('poition');
            $table->string('mobile_number');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('status')->default(0);
            $table->boolean('active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('site_building_id')->references('id')->on('site_buildings');
            $table->foreign('site_building_level_id')->references('id')->on('site_building_levels');
            $table->foreign('site_building_room_id')->references('id')->on('site_building_rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
