<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')
                  ->constrained('categories')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->string('nom', 150);
            $table->string('slug', 160)->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('lien', 500);
            $table->string('icone', 300)->nullable(); // chemin relatif storage/
            $table->boolean('actif')->default(true);
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->timestamps();

            // Recherche full-text rapide sur nom + description
            $table->index(['nom']);
            $table->index(['categorie_id', 'actif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};