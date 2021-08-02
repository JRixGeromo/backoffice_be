        <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateGroupingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_grouping', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('daily18')->nullable()->default(0)->index('daily18');
            $table->unsignedInteger('weekly18')->nullable()->default(0)->index('weekly18');
            $table->unsignedInteger('daily19')->nullable()->default(0)->index('daily19');
            $table->unsignedInteger('weekly19')->nullable()->default(0)->index('weekly19');
            $table->unsignedInteger('daily20')->nullable()->default(0)->index('daily20');
            $table->unsignedInteger('weekly20')->nullable()->default(0)->index('weekly20');
            $table->unsignedInteger('daily21')->nullable()->default(0)->index('daily21');
            $table->unsignedInteger('weekly21')->nullable()->default(0)->index('weekly21');
            $table->unsignedInteger('daily22')->nullable()->default(0)->index('daily22');
            $table->unsignedInteger('weekly22')->nullable()->default(0)->index('weekly22');
            $table->unsignedInteger('daily23')->nullable()->default(0)->index('daily23');
            $table->unsignedInteger('weekly23')->nullable()->default(0)->index('weekly23');
            $table->unsignedInteger('daily24')->nullable()->default(0)->index('daily24');
            $table->unsignedInteger('weekly24')->nullable()->default(0)->index('weekly24');
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
        Schema::dropIfExists('date_grouping');
    }
}
