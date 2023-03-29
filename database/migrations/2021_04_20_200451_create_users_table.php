<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_group_id')->constrained('user_groups')->onDelete('cascade');
            $table->integer('parent_id')->default(0);
            $table->string('name',100);
            $table->string('username',100)->unique();
            $table->string('slug',100)->unique();
            $table->string('email',100)->unique()->nullable();
            $table->string('mobile_no',50)->unique()->nullable();
            $table->string('password',255);
            $table->text('image_url')->nullable();
            $table->string('license_number',100)->nullable();
            $table->string('licence_state',100)->nullable();
            $table->enum('status',['1','0'])->default('1');
            $table->enum('is_email_verify',['1','0'])->default('0');
            $table->string('agent_agrement',225)->nullable();
            $table->enum('is_agrement_accept',['1','0'])->default('0');
            $table->dateTime('email_verify_at')->nullable();
            $table->enum('is_mobile_verify',['1','0'])->default('0');
            $table->dateTime('mobile_verify_at')->nullable();
            $table->string('country',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('zipcode',100)->nullable();
            $table->string('address',100)->nullable();
            $table->string('latitude',100)->nullable();
            $table->string('longitude',100)->nullable();
            $table->enum('online_status',['1','0'])->default('0');
            $table->string('mobile_otp',100)->nullable();
            $table->string('email_otp',100)->nullable();
            $table->integer('total_property')->default(0);
            $table->integer('total_rating')->default(0);
            $table->integer('total_review')->default(0);
            $table->string('website',200)->nullable();
            $table->text('about_us')->nullable();
            $table->text('tag_line')->nullable();
            $table->text('logo_url')->nullable();
            $table->string('share_profile_image')->nullable();
            $table->string('company_name')->nullable();
            $table->text('company_description')->nullable();
            $table->date('subscription_expiry_date')->nullable();
            $table->enum('invitation_acceptance',['1','0'])->default('0');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
 
            $table->index('user_group_id');
            $table->index('parent_id');
            $table->index('state');
            $table->index('name');
            $table->index('email');
            $table->index('licence_state');
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
        Schema::dropIfExists('notification_setting');
        Schema::dropIfExists('user_api_token');
        Schema::dropIfExists('users');
    }
}
