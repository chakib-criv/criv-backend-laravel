<?php

namespace App\Http\Controllers;

use App\Http\Requests\GareStoreRequest;
use App\Http\Requests\GareUpdateRequest;
use App\Models\Gare;
use App\Models\GareStatus;
use App\Models\ParticulariteGare;
use Illuminate\Support\Facades\DB;

class GareController extends Controller
{
    public function index()
    {
        return Gare::orderBy('name')->get();
    }

    public function show(Gare $gare)
    {
        return $gare;
    }

    public function store(GareStoreRequest $request)
    {
        $gare = Gare::create([
            'name'         => $request->input('name'),
            'ligne_rer_id' => $request->input('ligne_rer_id'),
        ]);

        return response()->json($gare, 201);
    }

    public function update(GareUpdateRequest $request, Gare $gare)
    {
        $gare->update($request->only(['name', 'ligne_rer_id']));
        return response()->json($gare);
    }

    public function destroy(Gare $gare)
    {
        try {
            DB::transaction(function () use ($gare) {
                // Nettoyage des dépendances pour éviter les orphelins
                if (class_exists(ParticulariteGare::class)) {
                    ParticulariteGare::where('gare_id', $gare->id)->delete();
                }
                if (class_exists(GareStatus::class)) {
                    GareStatus::where('gare_id', $gare->id)->delete();
                }
                $gare->delete();
            });
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Impossible de supprimer la gare', 'error' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Gare supprimée.']);
    }
}
