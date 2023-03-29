<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title',100);
            $table->string('slug',100)->unique();
            $table->text('image_url')->nullable();
            $table->text('address')->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('zipcode',100)->nullable();
            $table->string('property_type',50)->nullable();
            $table->text('mls_detail')->nullable();
            $table->string('asking_price',100)->nullable();
            $table->date('sell_date')->nullable();
            $table->string('cma_appointment',100)->nullable();
            $table->enum('property_status',['buying','selling'])->nullable();
            $table->enum('initiate_contract',['1','0'])->default('0');
            $table->integer('rating')->default('0');
            $table->integer('review')->nullable();
            $table->enum('status',['1','0'])->default('1');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->index('agent_id');
            $table->index('customer_id');
            $table->index('city');
            $table->index('state');
            $table->index('zipcode');
            $table->index('property_status');
            $table->index('initiate_contract');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
