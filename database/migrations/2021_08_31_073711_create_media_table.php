<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->integer('module_id');
            $table->string('filename')->nullable();
            $table->text('file_url')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->string('mime_type')->nullable();
            $table->text('meta_data')->nullable();
            $table->integer('status')->default('0');
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
        Schema::dropIfExists('media');
    }
}
