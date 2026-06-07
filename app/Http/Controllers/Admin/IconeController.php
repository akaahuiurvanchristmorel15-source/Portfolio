<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Icone;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IconeController extends Controller
{
    /**
     * Afficher la bibliothèque d'icônes
     */
    public function index()
    {
        $icones = Icone::latest()->paginate(24);
        return view('admin.icones.index', compact('icones'));
    }

    /**
     * Enregistrer réellement les icônes téléversées via Drag & Drop ou bouton
     */
    public function store(Request $request)
    {
        // Validation des fichiers reçus (fichiers[] d'après votre vue Blade)
        $request->validate([
            'fichiers' => 'required|array|max:10',
            'fichiers.*' => 'required|file|image|mimes:png,svg,jpg,jpeg,webp|max:512',
        ]);

        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $file) {
                // Récupération des informations du fichier
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();

                // Nettoyage et unicisation du nom du fichier physique
                $safeName = Str::slug($originalName) . '-' . time() . '.' . $extension;

                // Stockage physique dans le disque public (public/storage/icones/)
                $path = $file->storeAs('icones', $safeName, 'public');

                // Insertion exacte selon votre $fillable de Icone.php
                Icone::create([
                    'nom'       => $originalName,
                    'fichier'   => $path, // Correspond à votre colonne 'fichier'
                    'extension' => $extension,
                    'taille'    => $size,
                ]);
            }
        }

        // Renvoie une réponse JSON attendue par votre script XMLHttpRequest (onload)
        return response()->json([
            'success' => true,
            'message' => 'Importation réussie'
        ]);
    }

    /**
     * Renommer l'icône via la popup AJAX
     */
    public function rename(Request $request, Icone $icone)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $icone->update([
            'nom' => $request->nom
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer l'icône
     */
    public function destroy(Icone $icone)
    {
        // Votre modèle s'occupe déjà de supprimer le fichier physique dans booted() !
        // Il suffit de lancer la suppression en BDD
        $icone->delete();

        return redirect()
            ->route('admin.icones.index')
            ->with('success', 'Icône supprimée avec succès.');
    }
}