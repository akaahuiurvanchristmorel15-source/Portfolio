<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Categorie;
use App\Models\Icone;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * GET /admin
     */
    public function index(): View
    {
        $totalApps      = Application::count();
        $appsActives    = Application::where('actif', true)->count();
        $totalCategories= Categorie::count();
        $totalIcones    = Icone::count();

        $recentApps = Application::with('category')
            ->latest()
            ->limit(8)
            ->get();

        $categories = Categorie::withCount('applications')
            ->ordonnees()
            ->get();

        return view('admin.dashboard', compact(
            'totalApps',
            'appsActives',
            'totalCategories',
            'totalIcones',
            'recentApps',
            'categories',
        ));
    }
}