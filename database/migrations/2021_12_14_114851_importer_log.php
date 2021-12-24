<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importer_log', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('run_at');
            $table->string('entries_processed');
            $table->string('entries_created');
            $table->timestamps();
        });
    }
};
