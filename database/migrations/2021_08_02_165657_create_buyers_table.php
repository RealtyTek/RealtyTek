<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('slug',100)->unique();
            $table->text('requirements');
            $table->string('price');
            $table->string('house_type',50)->nullable();
            $table->string('move_date',50)->nullable();
            $table->string('first_time_buyer',50)->nullable();
            $table->string('pre_approved',50)->nullable();
            $table->string('company_name',100)->nullable();
            $table->string('address',100)->nullable();
            $table->string('amount',50)->nullable();
            $table->enum('status',['1','0'])->default('1');
            $table->enum('initiate_contract',['1','0'])->default('0');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->index('agent_id');
            $table->index('customer_id');
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
        Schema::dropIfExists('buyers');
    }
}
