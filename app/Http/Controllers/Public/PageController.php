<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $formations = Formation::query()
            ->publie()
            ->with(['devise', 'categorie'])
            ->latest('date_publication')
            ->take(6)
            ->get();

        return view('public.home', compact('formations'));
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function contact(): View
    {
        return view('public.contact');
    }
}
