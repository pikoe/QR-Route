<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique();
            $table->string('color', 7);
			$table->string('second_color', 7);
            $table->unsignedInteger('start_point_id')->nullable()->defaut(null);
            $table->timestamps();
			
			$table->foreign('start_point_id', 'fk_route_start_point')->references('id')->on('points')->onUpdate('cascade')->onDelete('set null');
        });
		Schema::table('points', function (Blueprint $table) {
			$table->foreign('route_id', 'fk_point_route')->references('id')->on('routes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
