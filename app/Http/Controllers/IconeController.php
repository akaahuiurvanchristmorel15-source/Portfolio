<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Icone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class IconeController extends Controller
{
    /* ───────────────────────────────────────────
     |  GET /admin/icones
     * ─────────────────────────────────────────── */
    public function index(): View
    {
        $icones = Icone::latest()->paginate(30);

        return view('admin.icones.index', compact('icones'));
    }

    /* ───────────────────────────────────────────
     |  POST /admin/icones
     |  Upload multiple (max 10 fichiers à la fois)
     * ─────────────────────────────────────────── */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'fichiers'   => 'required|array|min:1|max:10',
            'fichiers.*' => 'required|file|mimes:png,jpg,jpeg,svg,webp|max:512',
        ]);

        $uploadees = [];

        foreach ($request->file('fichiers') as $file) {
            $chemin = $file->store('icones/bibliotheque', 'public');

            $icone = Icone::create([
                'nom'       => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'fichier'   => $chemin,
                'extension' => $file->getClientOriginalExtension(),
                'taille'    => $file->getSize(),
            ]);

            $uploadees[] = $icone;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => count($uploadees) . ' icône(s) importée(s).',
                'icones'  => $uploadees->map(fn ($i) => [
                    'id'  => $i->id,
                    'nom' => $i->nom,
                    'url' => $i->url,
                ]),
            ]);
        }

        return back()->with('success', count($uploadees) . ' icône(s) importée(s).');
    }

    /* ───────────────────────────────────────────
     |  DELETE /admin/icones/{icone}
     |  Supprime fichier + enregistrement
     * ─────────────────────────────────────────── */
    public function destroy(Icone $icone): RedirectResponse|JsonResponse
    {
        // Le modèle gère la suppression physique du fichier via "deleting"
        $icone->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Icône supprimée.']);
        }

        return back()->with('success', 'Icône supprimée.');
    }

    /* ───────────────────────────────────────────
     |  GET /admin/icones/{icone}/rename  (optionnel)
     |  PUT /admin/icones/{icone}/rename
     * ─────────────────────────────────────────── */
    public function rename(Request $request, Icone $icone): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'nom' => 'required|string|max:150',
        ]);

        $icone->update($data);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Icône renommée.', 'icone' => $icone]);
        }

        return back()->with('success', 'Icône renommée.');
    }
}