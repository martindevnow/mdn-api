<?php

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
            $table->increments('id');

            $table->integer('client_id');

            $table->string('name');
            $table->string('code');
            $table->text('description');

            $table->enum('status', [
                'active',
                'hold',
                'hosting',
                'completed',
                'maintaining',
                'development',
                'production',
            ]);

            $table->dateTime('started_at');

            $table->string('git_repo_url')->nullable();
            $table->string('production_url')->nullable();
            $table->string('development_url')->nullable();

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
        Schema::drop('projects');
    }
}
