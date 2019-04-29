<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 社交账号关联表
 *
 * Class CreateUserSocialitesTable
 */
class CreateUserSocialitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_socialites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type')->comment('社交账号类型，如QQ，微信...');
            $table->string('openid');
            $table->string('nickname');
            $table->string('avatar');
            $table->timestamps();

            $table->unique('openid');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_socialites');
    }
}
