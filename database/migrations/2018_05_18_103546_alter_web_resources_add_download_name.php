<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWebResourcesAddDownloadName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("web_resources", function(Blueprint $table){
            $table->string("download_name")->default("file.ext");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("web_resources", function(Blueprint $table){
            $table->removeColumn("download_name");
        });
    }
}
