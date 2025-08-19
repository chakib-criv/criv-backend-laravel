<?php

namespace App\Http\Controllers;

use App\Models\ParticulariteGare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParticulariteGareController extends Controller
{
    /**
     * Affiche la liste des particularités.
     */
    public function index(Request $request)
    {
        $particularites = ParticulariteGare::with('user')->latest()->get();
        return response()->json($particularites);
    }

    /**
     * Stocke une nouvelle particularité.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'texte' => 'required|string',
            'gare_id' => 'required|exists:gares,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $particularite = new ParticulariteGare();
        $particularite->texte = $request->texte;
        $particularite->gare_id = $request->gare_id;
        $particularite->user_id = $request->user()->id;
        $particularite->save();

        $particularite->load('user');

        return response()->json($particularite, 201);
    }
    
    /**
     * Affiche une particularité spécifique.
     */
    public function show(ParticulariteGare $particulariteGare)
    {
        $particulariteGare->load(['gare', 'user']);
        return response()->json($particulariteGare);
    }

    /**
     * Met à jour une particularité existante.
     */
    public function update(Request $request, ParticulariteGare $particulariteGare)
    {
        if ($request->user()->role !== 'admin' && $request->user()->id !== $particulariteGare->user_id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'texte' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $particulariteGare->update($request->only(['texte']));

        return response()->json([
            'message' => 'Particularité mise à jour avec succès.',
            'particularite' => $particulariteGare
        ], 200);
    }

    /**
     * Supprime une particularité.
     * VERSION FINALE CORRIGÉE
     */
    public function destroy(Request $request, $id)
    {
        // On cherche manuellement la particularité par son ID.
        $particularite = ParticulariteGare::find($id);

        if (!$particularite) {
            return response()->json(['message' => 'Particularité non trouvée.'], 404);
        }

        // On vérifie les droits de l'utilisateur.
        if ($request->user()->role !== 'admin' && $request->user()->id !== $particularite->user_id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        // On supprime.
        $particularite->delete();

        return response()->json(['message' => 'Particularité supprimée avec succès.'], 200);
    }
}