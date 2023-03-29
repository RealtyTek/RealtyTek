<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id');
            $table->string('identifier');
            $table->integer('actor_id');
            $table->enum('actor_type',['users','cms_users'])->default('users');
            $table->integer('target_id');
            $table->enum('target_type',['users','cms_users'])->default('users');
            $table->integer('reference_id');
            $table->string('reference_slug');
            $table->string('reference_module');
            $table->string('title');
            $table->text('description');
            $table->text('custom_data');
            $table->text('web_redirect_link')->nullable();
            $table->enum('is_read',['0','1'])->default('0');
            $table->enum('is_view',['0','1'])->default('0');
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
        Schema::dropIfExists('notification');
    }
}
