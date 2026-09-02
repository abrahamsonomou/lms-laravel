<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Studio\StudioPage;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function show(StudioPage $page): View
    {
        abort_unless($page->active, 404);

        return view('public.landing', ['page' => $page]);
    }
}
