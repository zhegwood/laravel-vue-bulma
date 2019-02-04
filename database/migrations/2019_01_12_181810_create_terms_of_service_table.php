<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsOfServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms_of_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('text');
        });

        DB::table('terms_of_services')->insert([
            'name' => 'site_terms',
            'text' => '<strong>Site Terms of Service</strong>'
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terms_of_services');
    }
}
