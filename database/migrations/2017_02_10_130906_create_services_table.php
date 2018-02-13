<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_id');
            $table->string('description');
            $table->double('cost', 7, 2);
            $table->enum('billing_frequency', [
                'yearly',
                'monthly'
            ]);

            $table->dateTime('activated_at');
            $table->dateTime('deactivated_at')->nullable();
            $table->dateTime('valid_from_date');
            $table->dateTime('valid_until_date')->nullable();

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
        Schema::dropIfExists('services');
    }
}
