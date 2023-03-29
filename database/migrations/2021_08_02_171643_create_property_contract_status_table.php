<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyContractStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_contract_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('slug',100)->unique();
            $table->date('contract_offer')->nullable();
            $table->date('contract_countered')->nullable();
            $table->date('contract_accepted')->nullable();
            $table->date('contract_executed')->nullable();
            $table->date('offer_decline')->nullable();
            $table->date('inspection')->nullable();
            $table->date('appraisal')->nullable();
            $table->date('final_walk_thru')->nullable();
            $table->date('sattlement_date')->nullable();
            $table->text('add_comment')->nullable();
            $table->string('contract_status')->nullable();
            $table->text('contract_status_updated_date')->nullable();
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
        Schema::dropIfExists('property_contract_status');
    }
}
