<?php

namespace App\Http\Controllers\Api\V1\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\VilleRequest;
use App\Http\Resources\Core\VilleResource;
use App\Models\Core\Ville;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class VilleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $villes = Ville::query()
            ->when(request()->filled('pays_id'), fn ($query) => $query->where('pays_id', request()->integer('pays_id')))
            ->when(request()->boolean('active_only'), fn ($query) => $query->where('active', true))
            ->orderBy('nom')
            ->paginate(request()->integer('per_page', 20));

        return VilleResource::collection($villes);
    }

    public function store(VilleRequest $request): JsonResponse
    {
        $ville = Ville::query()->create($request->validated());

        return VilleResource::make($ville)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ville $ville): VilleResource
    {
        return VilleResource::make($ville->load('pays'));
    }

    public function update(VilleRequest $request, Ville $ville): VilleResource
    {
        $ville->update($request->validated());

        return VilleResource::make($ville);
    }

    public function destroy(Ville $ville): Response
    {
        $ville->delete();

        return response()->noContent();
    }
}
