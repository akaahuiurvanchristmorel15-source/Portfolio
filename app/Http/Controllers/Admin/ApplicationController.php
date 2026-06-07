<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Categorie;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * Liste des applications
     */
    public function index()
    {
        $applications = Application::latest()->paginate(10);
        $categories = Categorie::all();

        return view('admin.applications.index', compact('applications', 'categories'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = Categorie::all();

        return view('admin.applications.create', compact('categories'));
    }

    /**
     * Enregistrement d'une nouvelle application
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:applications,nom',
            'lien' => 'required|url',
            'categorie_id' => 'required|exists:categories,id',
        ], [
            'nom.unique' => 'Une application portant ce nom existe déjà.',
            'lien.url' => 'Le format du lien est invalide.',
        ]);

        Application::create([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'lien' => $request->lien,
            'categorie_id' => $request->categorie_id,
        ]);

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application créée avec succès.');
    }

    /**
     * Formulaire d'édition (C'est la méthode qui manquait !)
     */
    public function edit(Application $application)
    {
        $categories = Categorie::all();

        // Retourne la vue d'édition en lui passant l'application à modifier et les catégories
        return view('admin.applications.edit', compact('application', 'categories'));
    }

    /**
     * Mise à jour de l'application en base de données
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            // Le 'ignore' permet d'éviter que Laravel bloque si on garde le même nom durant la modification
            'nom' => 'required|string|max:255|unique:applications,nom,' . $application->id,
            'lien' => 'required|url',
            'categorie_id' => 'required|exists:categories,id',
        ], [
            'nom.unique' => 'Une application portant ce nom existe déjà.',
            'lien.url' => 'Le format du lien est invalide.',
        ]);

        $application->update([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'lien' => $request->lien,
            'categorie_id' => $request->categorie_id,
        ]);

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application mise à jour avec succès.');
    }

    /**
     * Suppression d'une application
     */
    public function destroy(Application $application)
    {
        $application->delete();

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application supprimée avec succès.');
    }

    /**
     * Mise à jour rapide du lien seul (votre route personnalisée)
     */
    public function updateLien(Request $request, Application $application)
    {
        $request->validate([
            'lien' => 'required|url'
        ]);

        $application->update([
            'lien' => $request->lien
        ]);

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Lien mis à jour avec succès.');
    }
}