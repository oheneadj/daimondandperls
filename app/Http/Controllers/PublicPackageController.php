<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\View\View;

class PublicPackageController extends Controller
{
    /**
     * Display the welcome page with active packages.
     */
    public function welcome(): View
    {
        $packages = Package::where('is_active', true)
            ->with('category')
            ->ordered()
            ->get();

        $categories = \App\Models\Category::whereHas('packages', function ($query) {
            $query->where('is_active', true);
        })->get();

        return view('welcome', compact('packages', 'categories'));
    }

    /**
     * Display the specified package details.
     */
    public function show(Package $package): View
    {
        // Ensure the package is active before showing it
        abort_if(! $package->is_active, 404);

        return view('packages.show', compact('package'));
    }
}
