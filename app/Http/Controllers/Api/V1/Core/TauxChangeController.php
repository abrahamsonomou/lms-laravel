<?php

namespace App\Http\Controllers\Api\V1\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\TauxChangeRequest;
use App\Http\Resources\Core\TauxChangeResource;
use App\Models\Core\TauxChange;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TauxChangeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $tauxChange = TauxChange::query()
            ->with(['deviseSource', 'deviseCible'])
            ->when(request()->boolean('active_only'), fn ($query) => $query->where('active', true))
            ->orderByDesc('date_effet')
            ->paginate(request()->integer('per_page', 20));

        return TauxChangeResource::collection($tauxChange);
    }

    public function store(TauxChangeRequest $request): JsonResponse
    {
        $tauxChange = TauxChange::query()->create($request->validated());

        return TauxChangeResource::make($tauxChange->load(['deviseSource', 'deviseCible']))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TauxChange $tauxChange): TauxChangeResource
    {
        return TauxChangeResource::make($tauxChange->load(['deviseSource', 'deviseCible']));
    }

    public function update(TauxChangeRequest $request, TauxChange $tauxChange): TauxChangeResource
    {
        $tauxChange->update($request->validated());

        return TauxChangeResource::make($tauxChange->load(['deviseSource', 'deviseCible']));
    }

    public function destroy(TauxChange $tauxChange): Response
    {
        $tauxChange->delete();

        return response()->noContent();
    }
}
