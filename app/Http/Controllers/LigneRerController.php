<?php

namespace App\Http\Controllers;

use App\Http\Requests\LigneRerStoreRequest;
use App\Http\Requests\LigneRerUpdateRequest;
use App\Models\LigneRer;
use Illuminate\Http\Request;

class LigneRerController extends Controller
{
    public function index()
    {
        return LigneRer::orderBy('name')->get();
    }

    public function show(LigneRer $ligneRer)
    {
        return $ligneRer;
    }

    public function store(LigneRerStoreRequest $request)
    {
        $ligne = LigneRer::create([
            'name'      => $request->input('name'),
            'color'     => $request->input('color'),
            'is_active' => (bool) $request->input('is_active', false),
        ]);

        return response()->json($ligne, 201);
    }

    public function update(LigneRerUpdateRequest $request, LigneRer $ligneRer)
    {
        $ligneRer->update($request->only(['name', 'color', 'is_active']));
        return response()->json($ligneRer);
    }

    public function destroy(LigneRer $ligneRer)
    {
        $ligneRer->delete();
        return response()->json(['message' => 'Ligne supprimée.']);
    }

    /**
     * (EXISTANT) Bascule le statut actif/inactif d’une ligne.
     * Route: POST /lignes-rer/{ligneRer}/toggle-status
     */
    public function toggleStatus(LigneRer $ligneRer)
    {
        $ligneRer->is_active = !$ligneRer->is_active;
        $ligneRer->save();

        return response()->json([
            'message'    => 'Statut de la ligne mis à jour.',
            'ligne_rer'  => $ligneRer,
        ]);
    }
}
