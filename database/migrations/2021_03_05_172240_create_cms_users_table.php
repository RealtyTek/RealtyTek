<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_role_id')->constrained('cms_roles')->onDelete('cascade');
            $table->integer('user_ref_id')->default(0);
            $table->string('name',50);
            $table->string('username',50)->unique();
            $table->string('slug',50)->unique();
            $table->string('email',50)->unique();
            $table->string('mobile_no',50)->unique()->nullable();
            $table->string('password');
            $table->text('image_url')->nullable();
            $table->text('share_profile_image')->nullable();
            $table->enum('status',['1','0'])->default('1');
            $table->enum('is_email_verify',['0','1'])->default('0');
            $table->rememberToken();
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
        Schema::dropIfExists('cms_users');
    }
}
