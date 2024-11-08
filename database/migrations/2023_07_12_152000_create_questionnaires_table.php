<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->string('serial_number')->nullable();
            $table->mediumText('questions')->nullable();      
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes()->index();
        });
        
        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('questionnaire_id')->unsigned();
            $table->mediumText('answer')->nullable();      
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
        });

        Schema::create('site_building_rooms', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned();
            $table->bigInteger('site_building_id')->unsigned();
            $table->bigInteger('site_building_level_id')->unsigned();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('site_building_id')->references('id')->on('site_buildings');
            $table->foreign('site_building_level_id')->references('id')->on('site_building_levels');
        });

        Schema::create('questionnaire_surveys', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('questionnaire_id')->unsigned();
            $table->bigInteger('questionnaire_answer_id')->unsigned();

            $table->bigInteger('site_id')->unsigned();
            $table->bigInteger('site_building_id')->unsigned();
            $table->bigInteger('site_building_level_id')->unsigned();
            $table->bigInteger('site_building_room_id')->unsigned();
            $table->mediumText('remarks')->nullable();
            $table->integer('status')->default(0);          
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
            $table->foreign('questionnaire_answer_id')->references('id')->on('questionnaire_answers');
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
        Schema::dropIfExists('questionnaire_surveys');
        Schema::dropIfExists('site_building_rooms');
        Schema::dropIfExists('questionnaire_answers');
        Schema::dropIfExists('questionnaires');
    }
}
