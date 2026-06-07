<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Categorie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    /* ───────────────────────────────────────────
     |  GET /admin/applications
     |  Liste paginée + filtres (catégorie / search)
     * ─────────────────────────────────────────── */
    public function index(Request $request): View
    {
        $query = Application::with('category')->ordonnees();

        if ($request->filled('categorie')) {
            $query->parCategorie((int) $request->categorie);
        }

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $applications = $query->paginate(20)->withQueryString();
        $categories   = Categorie::ordonnees()->get();

        return view('admin.applications.index', compact('applications', 'categories'));
    }

    /* ───────────────────────────────────────────
     |  GET /admin/applications/create
     * ─────────────────────────────────────────── */
    public function create(): View
    {
        $categories = Categorie::ordonnees()->get();

        return view('admin.applications.create', compact('categories'));
    }

    /* ───────────────────────────────────────────
     |  POST /admin/applications
     * ─────────────────────────────────────────── */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom'          => 'required|string|max:150',
            'description'  => 'nullable|string|max:2000',
            'lien'         => 'required|url|max:500',
            'categorie_id' => 'required|exists:categories,id',
            'icone'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:512',
            'actif'        => 'boolean',
            'ordre'        => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('icone')) {
            $data['icone'] = $request->file('icone')
                ->store('icones/applications', 'public');
        }

        $data['actif'] = $request->boolean('actif', true);

        Application::create($data);

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application créée avec succès.');
    }

    /* ───────────────────────────────────────────
     |  GET /admin/applications/{application}/edit
     * ─────────────────────────────────────────── */
    public function edit(Application $application): View
    {
        $categories = Categorie::ordonnees()->get();

        return view('admin.applications.edit', compact('application', 'categories'));
    }

    /* ───────────────────────────────────────────
     |  PUT /admin/applications/{application}
     * ─────────────────────────────────────────── */
    public function update(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate([
            'nom'          => 'required|string|max:150',
            'description'  => 'nullable|string|max:2000',
            'lien'         => 'required|url|max:500',
            'categorie_id' => 'required|exists:categories,id',
            'icone'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:512',
            'actif'        => 'boolean',
            'ordre'        => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('icone')) {
            // Supprime l'ancienne icône
            if ($application->icone) {
                Storage::disk('public')->delete($application->icone);
            }
            $data['icone'] = $request->file('icone')
                ->store('icones/applications', 'public');
        }

        $data['actif'] = $request->boolean('actif', $application->actif);

        $application->update($data);

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application mise à jour.');
    }

    /* ───────────────────────────────────────────
     |  PATCH /admin/applications/{application}/lien
     |  Mise à jour rapide du lien uniquement
     * ─────────────────────────────────────────── */
    public function updateLien(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate([
            'lien' => 'required|url|max:500',
        ]);

        $application->update($data);

        return back()->with('success', 'Lien mis à jour.');
    }

    /* ───────────────────────────────────────────
     |  DELETE /admin/applications/{application}
     * ─────────────────────────────────────────── */
    public function destroy(Application $application): RedirectResponse
    {
        // Le modèle supprime l'icône via son event "deleting"
        $application->delete();

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application supprimée.');
    }
}