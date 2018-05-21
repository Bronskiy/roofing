<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateMailboxTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('mailbox',function(Blueprint $table){
            $table->increments("id");
            $table->string("from_name")->nullable();
            $table->string("from_email")->nullable();
            $table->string("subject")->nullable();
            $table->text("mail_body")->nullable();
            $table->tinyInteger("export_check")->default(0)->nullable();
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
        Schema::drop('mailbox');
    }

}