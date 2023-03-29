<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('buyers')->onDelete('cascade');
            $table->string('slug',100)->unique();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status',['pending','accept','reject'])->default('pending');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->index('agent_id');
            $table->index('customer_id');
            $table->index('property_id');
            $table->index('appointment_date');
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
        Schema::dropIfExists('appointments');
    }
}
