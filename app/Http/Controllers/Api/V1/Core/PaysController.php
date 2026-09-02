<?php

namespace App\Http\Controllers\Api\V1\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\PaysRequest;
use App\Http\Resources\Core\PaysResource;
use App\Models\Core\Pays;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PaysController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $pays = Pays::query()
            ->when(request()->boolean('active_only'), fn ($query) => $query->where('active', true))
            ->when(request()->filled('search'), fn ($query) => $query->where('nom', 'like', '%'.request('search').'%'))
            ->orderBy('nom')
            ->paginate(request()->integer('per_page', 20));

        return PaysResource::collection($pays);
    }

    public function store(PaysRequest $request): JsonResponse
    {
        $pays = Pays::query()->create($request->validated());

        return PaysResource::make($pays)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Pays $pays): PaysResource
    {
        return PaysResource::make($pays->load('villes'));
    }

    public function update(PaysRequest $request, Pays $pays): PaysResource
    {
        $pays->update($request->validated());

        return PaysResource::make($pays);
    }

    public function destroy(Pays $pays): Response
    {
        $pays->delete();

        return response()->noContent();
    }
}
