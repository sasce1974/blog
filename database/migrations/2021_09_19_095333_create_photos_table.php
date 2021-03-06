<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('alt')->nullable();
            $table->boolean('approved')->default(1);
            $table->integer('photoable_id');
            $table->string('photoable_type');
            $table->timestamps();
        });


        // Call seeder
        Artisan::call('db:seed', [
            '--verbose' => 3,
            '--force' => true
        ]);
        dd(Artisan::output());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
