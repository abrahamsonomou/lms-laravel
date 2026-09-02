<?php

use App\Http\Controllers\Api\V1\Core\DeviseController;
use App\Http\Controllers\Api\V1\Core\LangueController;
use App\Http\Controllers\Api\V1\Core\PaysController;
use App\Http\Controllers\Api\V1\Core\TauxChangeController;
use App\Http\Controllers\Api\V1\Core\VilleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    // Module Core / Paramétrage
    Route::apiResource('langues', LangueController::class);
    Route::apiResource('devises', DeviseController::class);
    Route::apiResource('pays', PaysController::class)->parameters(['pays' => 'pays']);
    Route::apiResource('villes', VilleController::class);
    Route::apiResource('taux-change', TauxChangeController::class)->parameters(['taux-change' => 'tauxChange']);
});
