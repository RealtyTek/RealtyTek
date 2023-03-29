<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title',150);
            $table->string('slug',150)->unique();
            $table->text('description');
            $table->integer('trial_period')->default(0);
            $table->string('duration');
            $table->enum('duration_unit',['days','week','month','year']);
            $table->string('amount',100);
            $table->enum('status',[0,1])->default(1);
            $table->timestamps(); 
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_packages');
    }
}
