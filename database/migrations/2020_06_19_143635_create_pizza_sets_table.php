<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePizzaSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pizza_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('image', 100)->nullable();
            $table->float('cost', 8, 2)->nullable();
            $table->integer('weight')->nullable();
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
        Schema::dropIfExists('pizza_sets');
    }
}
