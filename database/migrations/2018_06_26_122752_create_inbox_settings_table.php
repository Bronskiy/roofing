<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateInboxSettingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('inboxsettings',function(Blueprint $table){
            $table->increments("id");
            $table->integer("accounts_id")->references("id")->on("accounts");
            $table->string("inbox_settings_sender");
            $table->date("inbox_settings_date");
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
        Schema::drop('inboxsettings');
    }

}