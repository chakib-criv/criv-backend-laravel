<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParcoursController;
use App\Http\Controllers\LigneRerController;
use App\Http\Controllers\GareController;
use App\Http\Controllers\ParticulariteGareController;

// --- Routes d'authentification Publiques ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Routes Protégées par Sanctum ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- CRIVs (Parcours) ---
    // Lecture
    Route::apiResource('parcours', ParcoursController::class)->only(['index', 'show']);
    // Écriture réservée aux admins
    Route::middleware('can:admin-only')->group(function () {
        Route::apiResource('parcours', ParcoursController::class)->only(['store', 'update', 'destroy']);
    });

    // --- Lignes RER ---
    // Lecture
    Route::apiResource('lignes-rer', LigneRerController::class)->only(['index', 'show']);
    // Écriture réservée aux admins
    Route::middleware('can:admin-only')->group(function () {
        Route::apiResource('lignes-rer', LigneRerController::class)->only(['store', 'update', 'destroy']);
    });

    // --- Gares ---
    // Lecture
    Route::apiResource('gares', GareController::class)->only(['index', 'show']);
    // Écriture réservée aux admins
    Route::middleware('can:admin-only')->group(function () {
        Route::apiResource('gares', GareController::class)->only(['store', 'update', 'destroy']);
    });

    // --- Particularités (inchangé) ---
    Route::apiResource('particularites-gare', ParticulariteGareController::class);

    // --- ROUTES SPÉCIFIQUES EXISTANTES ---
    Route::post('/parcours/toggle-ligne', [ParcoursController::class, 'toggleLigneAssociation']);
    // NB: paramètre {parcour} = singulier automatique de "parcours"
    Route::post('/parcours/{parcour}/toggle-status', [ParcoursController::class, 'toggleStatus']);

    Route::post('/parcours/toggle-gare-status', [ParcoursController::class, 'toggleGareStatus']);
    Route::get('/gare-statuses', [ParcoursController::class, 'getGareStatuses']);

    // Lignes: toggle-status (conservé)
    Route::post('/lignes-rer/{ligneRer}/toggle-status', [LigneRerController::class, 'toggleStatus']);
});
