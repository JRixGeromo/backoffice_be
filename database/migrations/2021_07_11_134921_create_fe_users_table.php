<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fe_users', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->index('uid');
            $table->integer('pid')->nullable();
            $table->integer('tstamp')->nullable();
            $table->integer('crdate')->nullable();
            $table->integer('cruser_id')->nullable();
            $table->smallInteger('deleted')->nullable();
            $table->smallInteger('disable')->nullable();
            $table->integer('starttime')->nullable();
            $table->integer('endtime')->nullable();
            $table->text('description')->nullable();
            $table->string('tx_extbase_type', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('password', 100)->nullable();
            $table->text('usergroup')->nullable();
            $table->string('name', 160)->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('lock_to_domain', 50)->nullable();
            $table->binary('uc')->nullable();
            $table->string('title', 40)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('country', 40)->nullable();
            $table->string('www', 80)->nullable();
            $table->string('company', 80)->nullable();
            $table->text('image')->nullable();
            $table->text('TSconfig')->nullable();
            $table->string('lastlogin', 80)->nullable();
            $table->integer('is_online')->nullable();
            $table->text('felogin_redirectPid')->nullable();
            $table->string('felogin_forgotHash', 80)->nullable();
            $table->string('tx_shop_wishlist_hash', 255)->nullable();
            $table->text('tx_rest_apikey')->nullable();
            $table->integer('tx_shop_is_guest')->nullable();
            $table->integer('tx_shop_unsubscribed')->nullable();
            $table->integer('tx_shop_is_unsubscribed')->nullable();
            $table->string('user_type',15)->nullable();
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
        Schema::dropIfExists('fe_users');
    }
}
