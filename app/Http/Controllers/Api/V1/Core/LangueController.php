<?php

namespace App\Http\Controllers\Api\V1\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\LangueRequest;
use App\Http\Resources\Core\LangueResource;
use App\Models\Core\Langue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class LangueController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $langues = Langue::query()
            ->when(request()->boolean('active_only'), fn ($query) => $query->where('active', true))
            ->orderBy('nom')
            ->paginate(request()->integer('per_page', 20));

        return LangueResource::collection($langues);
    }

    public function store(LangueRequest $request): JsonResponse
    {
        $langue = Langue::query()->create($request->validated());

        return LangueResource::make($langue)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Langue $langue): LangueResource
    {
        return LangueResource::make($langue);
    }

    public function update(LangueRequest $request, Langue $langue): LangueResource
    {
        $langue->update($request->validated());

        return LangueResource::make($langue);
    }

    public function destroy(Langue $langue): Response
    {
        $langue->delete();

        return response()->noContent();
    }
}
