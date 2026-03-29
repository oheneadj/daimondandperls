<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\View\View;

class PublicPackageController extends Controller
{
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
