<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    /**
     * Liste toutes les catégories
     */
    public function index()
    {
        $categories = Categorie::orderBy('ordre')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Enregistrement d'une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'icone' => 'nullable|string|max:255', 
            'ordre' => 'nullable|integer',
        ], [
            'nom.unique' => 'Cette catégorie existe déjà.',
        ]);

        Categorie::create([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'icone' => $request->icone,
            'ordre' => $request->ordre ?? 0,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Formulaire de modification
     */
    public function edit(Categorie $category)
    {
        // Option 1 : On renomme la variable à la volée pour votre Blade actuel
        $categorie = $category; 

        // Désormais compact('categorie') transmet bien la variable $categorie à la vue edit.blade.php
        return view('admin.categories.edit', compact('categorie'));
    }

    /**
     * Mise à jour de la catégorie en BDD
     */
    public function update(Request $request, Categorie $category)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
            'icone' => 'nullable|string|max:255',
            'ordre' => 'nullable|integer',
        ], [
            'nom.unique' => 'Cette catégorie existe déjà.',
        ]);

        $category->update([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'icone' => $request->icone,
            'ordre' => $request->ordre ?? 0,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Suppression d'une catégorie
     */
    public function destroy(Categorie $category)
    {
        if ($category->applications()->count() > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des applications.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}