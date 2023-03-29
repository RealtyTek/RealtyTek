<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyLoanInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_loan_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('slug',100)->unique();
            $table->string('company',150)->nullable();
            $table->string('contact',150)->nullable();
            $table->string('contact_number',50)->nullable();
            $table->string('sale_price',150)->nullable();
            $table->string('financing',150)->nullable();
            $table->string('emd_submitted',150)->nullable();
            $table->string('down_payment',150)->nullable();
            $table->string('loan_status',150)->nullable();
            $table->date('loan_status_updated_date',150)->nullable();
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
        Schema::dropIfExists('property_loan_info');
    }
}
