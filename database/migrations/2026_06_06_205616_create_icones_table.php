<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bibliothèque d'icônes indépendante des applications.
     * Une icône peut être réutilisée sur plusieurs applications.
     */
    public function up(): void
    {
        Schema::create('icones', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);               // nom affiché (ex: "Logo Figma")
            $table->string('fichier', 300)->unique(); // chemin storage/ (ex: icones/figma.png)
            $table->string('extension', 10);          // png | svg | webp
            $table->unsignedInteger('taille')->nullable(); // taille fichier en octets
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('icones');
    }
};