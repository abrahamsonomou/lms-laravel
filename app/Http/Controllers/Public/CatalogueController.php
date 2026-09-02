<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\CategorieFormation;
use App\Models\Catalogue\Formation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogueController extends Controller
{
    public function index(Request $request): View
    {
        $formations = Formation::query()
            ->publie()
            ->with(['categorie', 'devise'])
            ->when($request->filled('categorie'), fn ($query) => $query->whereHas('categorie', fn ($q) => $q->where('code', $request->string('categorie'))))
            ->when($request->filled('search'), fn ($query) => $query->where('titre', 'like', '%'.$request->string('search').'%'))
            ->latest('date_publication')
            ->paginate(9)
            ->withQueryString();

        $categories = CategorieFormation::query()
            ->where('active', true)
            ->withCount(['formations' => fn ($query) => $query->publie()])
            ->orderBy('nom')
            ->get();

        return view('public.catalogue.index', compact('formations', 'categories'));
    }

    public function show(Formation $formation): View
    {
        abort_unless($formation->isPublie(), 404);

        $formation->load(['categorie', 'devise', 'createur', 'cours' => fn ($query) => $query->orderBy('ordre')]);

        return view('public.catalogue.show', compact('formation'));
    }
}
