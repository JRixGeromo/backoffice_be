<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTtAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tt_address', function (Blueprint $table) {
            $table->integer('uid')->primary();
            $table->integer('pid')->nullable();
            $table->integer('tstamp')->nullable();
            $table->integer('crdate')->nullable();
            $table->integer('cruser_id')->nullable();
            $table->smallInteger('deleted')->nullable();
            $table->smallInteger('hidden')->nullable();
            $table->integer('sorting')->nullable();
            $table->integer('sys_language_uid')->nullable();
            $table->integer('l10n_parent')->nullable();
            $table->binary('l10n_diffsource')->nullable();
            $table->integer('t3ver_oid')->nullable();
            $table->integer('t3ver_id')->nullable();
            $table->integer('t3ver_wsid')->nullable();
            $table->string('t3ver_label', 30)->nullable();
            $table->smallInteger('t3ver_state')->nullable();
            $table->smallInteger('t3ver_stage')->nullable();
            $table->integer('t3ver_count')->nullable();
            $table->integer('t3ver_tstamp')->nullable();
            $table->integer('t3ver_move_id')->nullable();
            $table->integer('t3_origuid')->nullable();
            $table->text('l10n_state')->nullable();
            $table->string('gender', 1)->nullable();
            $table->text('name')->nullable();
            $table->string('slug', 2048)->nullable();
            $table->text('first_name')->nullable();
            $table->text('middle_name')->nullable();
            $table->text('last_name')->nullable();
            $table->bigInteger('birthday')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('www', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('building', 20)->nullable();
            $table->string('room', 15)->nullable();
            $table->string('company', 255)->nullable();
            $table->string('position', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('region', 255)->nullable();
            $table->string('country', 128)->nullable();
            $table->string('fax', 30)->nullable();
            $table->text('description')->nullable();
            $table->string('skype', 255)->nullable();
            $table->string('twitter', 255)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->decimal('latitude', 14, 12)->nullable();
            $table->decimal('longitude', 15, 12)->nullable();
            $table->binary('image')->nullable();
            $table->integer('categories')->nullable();
            $table->unsignedInteger('fe_user')->nullable()->index('fe_user');
            $table->string('address_type', 255)->nullable();
            $table->string('salutation', 255)->nullable();
            $table->string('department', 255)->nullable();
            $table->string('taxIdentificationNumber', 255)->nullable();
            $table->string('tax_identification_number', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tt_address');
    }
}
