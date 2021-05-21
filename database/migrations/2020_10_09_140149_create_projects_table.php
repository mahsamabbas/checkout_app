<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('url', 255);
            $table->float('reservation_cost');
            $table->string('mailchimp_server', 191);
            $table->string('mailchimp_api_key', 191)->unique();
            $table->string('mailchimp_list_id', 255);
            $table->string('lead_redirect_url', 255);
            $table->string('reservation_redirect_url', 255);
            $table->integer('data_app_project_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
