<?php

namespace App\Http\Controllers\Api\V1\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\DeviseRequest;
use App\Http\Resources\Core\DeviseResource;
use App\Models\Core\Devise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class DeviseController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $devises = Devise::query()
            ->when(request()->boolean('active_only'), fn ($query) => $query->where('active', true))
            ->orderBy('code')
            ->paginate(request()->integer('per_page', 20));

        return DeviseResource::collection($devises);
    }

    public function store(DeviseRequest $request): JsonResponse
    {
        $devise = Devise::query()->create($request->validated());

        return DeviseResource::make($devise)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Devise $devise): DeviseResource
    {
        return DeviseResource::make($devise);
    }

    public function update(DeviseRequest $request, Devise $devise): DeviseResource
    {
        $devise->update($request->validated());

        return DeviseResource::make($devise);
    }

    public function destroy(Devise $devise): Response
    {
        $devise->delete();

        return response()->noContent();
    }
}
