<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Categorie;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * GET /services/applications
     * Page publique – liste des applications avec filtres.
     */
    public function index(): View
    {
        $applications = Application::with('category')
            ->actives()
            ->ordonnees()
            ->get();

        $categories = Categorie::ordonnees()->get();

        return view('services.applications.index', compact('applications', 'categories'));
    }
}