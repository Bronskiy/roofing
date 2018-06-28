<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateInboxTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('inbox',function(Blueprint $table){
            $table->increments("id");
            $table->string("inbox_sender")->nullable();
            $table->string("inbox_date")->nullable();
            $table->string("inbox_subject")->nullable();
            $table->longtext("inbox_text_body")->nullable();
            $table->longtext("inbox_html_body")->nullable();
            $table->longtext("inbox_edited_body")->nullable();
            $table->string("inbox_leads_count")->nullable();
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
        Schema::drop('inbox');
    }

}
