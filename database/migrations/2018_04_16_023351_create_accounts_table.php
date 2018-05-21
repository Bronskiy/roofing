<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateAccountsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('accounts',function(Blueprint $table){
            $table->increments("id");
            $table->string("host")->nullable();
            $table->string("port")->nullable();
            $table->string("encryption")->nullable();
            $table->tinyInteger("validate_cert")->default(1)->nullable();
            $table->string("username")->nullable();
            $table->string("password")->nullable();
            $table->string("name")->nullable();
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
        Schema::drop('accounts');
    }

}