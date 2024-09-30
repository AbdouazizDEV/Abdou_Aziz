<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apprenants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->dateTime('date_naissance')->nullable();
            $table->string('sexe')->nullable();
            $table->unsignedBigInteger('referentiel_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('matricule')->unique();
            $table->bigInteger('qr_code')->nullable();
            $table->string('is_active')->default('OUI');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apprenants');
    }
};
