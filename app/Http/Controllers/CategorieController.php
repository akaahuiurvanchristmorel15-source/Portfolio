<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategorieController extends Controller
{
    /* ───────────────────────────────────────────
     |  GET /admin/categories
     * ─────────────────────────────────────────── */
    public function index(): View
    {
        $categories = Categorie::withCount('applications')
            ->ordonnees()
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /* ───────────────────────────────────────────
     |  GET /admin/categories/create
     * ─────────────────────────────────────────── */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /* ───────────────────────────────────────────
     |  POST /admin/categories
     * ─────────────────────────────────────────── */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100|unique:categories,nom',
            'description' => 'nullable|string|max:500',
            'ordre'       => 'nullable|integer|min:0',
        ]);

        Categorie::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie créée.');
    }

    /* ───────────────────────────────────────────
     |  GET /admin/categories/{categorie}/edit
     * ─────────────────────────────────────────── */
    public function edit(Categorie $categorie): View
    {
        return view('admin.categories.edit', compact('categorie'));
    }

    /* ───────────────────────────────────────────
     |  PUT /admin/categories/{categorie}
     * ─────────────────────────────────────────── */
    public function update(Request $request, Categorie $categorie): RedirectResponse
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable|string|max:500',
            'ordre'       => 'nullable|integer|min:0',
        ]);

        $categorie->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    /* ───────────────────────────────────────────
     |  DELETE /admin/categories/{categorie}
     |  Bloqué si des applications sont rattachées
     |  (restrictOnDelete en base)
     * ─────────────────────────────────────────── */
    public function destroy(Categorie $categorie): RedirectResponse
    {
        if ($categorie->applications()->exists()) {
            return back()->with(
                'error',
                'Impossible de supprimer cette catégorie : des applications y sont rattachées.'
            );
        }

        $categorie->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée.');
    }
}