<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultUserToUsersTable extends Migration
{
    public function up()
    {
        \Database\Factories\UserFactory::new()->create([
            "name" => "Mike",
            "email" => "mike@launchboom.com"
        ]);
    }

}