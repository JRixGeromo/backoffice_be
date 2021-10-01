<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderImportTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_import_trackers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('uid')->nullable()->default(0);
            $table->string('order_number')->nullable()->default('')->index('order_number');
            $table->string('order_status', 50)->nullable()->default('');
            $table->boolean('import_status')->nullable()->default(0)->index('uid');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_import_trackers');
    }
}
