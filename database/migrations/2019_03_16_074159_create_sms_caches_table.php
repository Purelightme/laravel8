<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 验证码存储介质|若采用db存储介质，可以使用此表
 *
 * Class CreateSmsCachesTable
 */
class CreateSmsCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_caches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scene')->comment('场景');
            $table->string('phone')->comment('手机号');
            $table->string('code')->comment('验证码');
            $table->dateTime('expires_at')->comment('验证码过期时间');
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
        Schema::dropIfExists('sms_caches');
    }
}
