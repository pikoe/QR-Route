<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('route_id')->nullable()->defaut(null);
            $table->string('code', 255)->nullable()->defaut(null)->unique();
			$table->double('lat', 10, 8);
			$table->double('lng', 11, 8);
			$table->unsignedInteger('next_point_id')->nullable()->defaut(null)->unique();
            $table->timestamps();
			
			$table->foreign('next_point_id', 'fk_point_next_point')->references('id')->on('points')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points');
    }
}
