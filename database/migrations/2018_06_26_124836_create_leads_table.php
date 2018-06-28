<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateLeadsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('leads',function(Blueprint $table){
            $table->increments("id");
            $table->string("lead_name")->nullable();
            $table->string("lead_phones")->nullable();
            $table->string("lead_time")->nullable();
            $table->string("lead_roof_age")->nullable();
            $table->string("lead_foor_type")->nullable();
            $table->string("lead_address")->nullable();
            $table->text("lead_notes")->nullable();
            $table->integer("inbox_id")->references("id")->on("inbox")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('leads');
    }

}