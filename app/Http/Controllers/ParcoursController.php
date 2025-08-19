<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParcoursStoreRequest;
use App\Http\Requests\ParcoursUpdateRequest;
use App\Models\Parcours;
use App\Models\LigneRer;
use App\Models\Gare;
use App\Models\GareStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <-- AJOUT : pour récupérer l’utilisateur

class ParcoursController extends Controller
{
    /**
     * LISTE des parcours (avec leurs lignes)
     * - Admin : tous les parcours
     * - Utilisateur : seulement les parcours qui lui sont attribués (pivot parcours_user)
     */
    public function index()
    {
        $user = Auth::user();

        // Si pas connecté (par précaution), on renvoie vide
        if (!$user) {
            return collect();
        }

        // Admin : tout voir
        if (isset($user->role) && $user->role === 'admin') {
            return Parcours::with('lignes_rer')
                ->orderBy('name')
                ->get();
        }

        // Utilisateur : seulement ses parcours autorisés
        return Parcours::with('lignes_rer')
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * DÉTAIL d’un parcours (avec ses lignes)
     * - Admin : OK
     * - Utilisateur : seulement si le parcours lui est attribué
     */
    public function show(Parcours $parcours)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Admin : OK
        if (isset($user->role) && $user->role === 'admin') {
            return $parcours->load('lignes_rer');
        }

        // Utilisateur : vérifier qu’il a le droit sur ce parcours
        $hasAccess = $parcours->users()->where('users.id', $user->id)->exists();
        if (!$hasAccess) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $parcours->load('lignes_rer');
    }

    /**
     * CRÉER un parcours (admin)
     */
    public function store(ParcoursStoreRequest $request)
    {
        $parcours = Parcours::create([
            'name' => $request->input('name'),
            'is_active' => (bool) $request->input('is_active', false),
        ]);

        return response()->json($parcours->load('lignes_rer'), 201);
    }

    /**
     * METTRE À JOUR un parcours (admin)
     */
    public function update(ParcoursUpdateRequest $request, Parcours $parcours)
    {
        $parcours->update($request->only(['name', 'is_active']));

        return response()->json($parcours->load('lignes_rer'));
    }

    /**
     * SUPPRIMER un parcours (admin)
     */
    public function destroy(Parcours $parcours)
    {
        // On détache les relations pour éviter des orphelins en pivot
        try {
            DB::transaction(function () use ($parcours) {
                // Détache les lignes si relation N-N
                if (method_exists($parcours, 'lignes_rer')) {
                    $parcours->lignes_rer()->detach();
                }

                // Supprime les statuts de gares liés à ce parcours si la table existe
                if (class_exists(GareStatus::class)) {
                    GareStatus::where('parcours_id', $parcours->id)->delete();
                }

                $parcours->delete();
            });
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Impossible de supprimer le parcours', 'error' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Parcours supprimé.']);
    }

    /**
     * (EXISTANT) Bascule le statut actif/inactif d’un parcours.
     *
     * NOTE IMPORTANTE :
     * La route est : POST /parcours/{parcour}/toggle-status
     * Laravel fait le singulier de "parcours" => "parcour" pour le paramètre de binding.
     */
    public function toggleStatus(Parcours $parcour)
    {
        $parcour->is_active = !$parcour->is_active;
        $parcour->save();

        return response()->json([
            'message'  => 'Statut du parcours mis à jour.',
            'parcours' => $parcour->load('lignes_rer'),
        ]);
    }

    /**
     * (EXISTANT) Associe/dissocie une ligne à un parcours.
     * Body attendu: { parcours_id, ligne_rer_id }
     */
    public function toggleLigneAssociation(Request $request)
    {
        $request->validate([
            'parcours_id'   => ['required', 'integer', 'exists:parcours,id'],
            'ligne_rer_id'  => ['required', 'integer', 'exists:ligne_rers,id'],
        ]);

        $parcours = Parcours::findOrFail($request->parcours_id);
        $ligne    = LigneRer::findOrFail($request->ligne_rer_id);

        // On suppose une relation belongsToMany
        $attached = $parcours->lignes_rer()->where('ligne_rer_id', $ligne->id)->exists();

        if ($attached) {
            $parcours->lignes_rer()->detach($ligne->id);
            $action = 'détachée';
        } else {
            $parcours->lignes_rer()->attach($ligne->id);
            $action = 'attachée';
        }

        return response()->json([
            'message'  => "Ligne {$action} au parcours.",
            'parcours' => $parcours->load('lignes_rer'),
        ]);
    }

    /**
     * (EXISTANT) Bascule l’état actif/inactif d’une gare pour un parcours.
     * Body attendu: { parcours_id, gare_id }
     *
     * Convention:
     * - Table "gare_statuses" avec colonnes: id, parcours_id, gare_id, is_active (bool)
     * - Si aucune entrée n’existe => on crée is_active=false pour "désactiver" la gare
     * (car par défaut une gare est considérée active si absence de ligne).
     */
    public function toggleGareStatus(Request $request)
    {
        $request->validate([
            'parcours_id' => ['required', 'integer', 'exists:parcours,id'],
            'gare_id'     => ['required', 'integer', 'exists:gares,id'],
        ]);

        $parcours = Parcours::findOrFail($request->parcours_id);
        $gare     = Gare::findOrFail($request->gare_id);

        // On cherche un enregistrement existant
        $status = GareStatus::where('parcours_id', $parcours->id)
            ->where('gare_id', $gare->id)
            ->first();

        if (!$status) {
            // Si rien n’existe, on crée une désactivation explicite
            $status = GareStatus::create([
                'parcours_id' => $parcours->id,
                'gare_id'     => $gare->id,
                'is_active'   => false,
            ]);
        } else {
            // Sinon on inverse
            $status->is_active = !$status->is_active;
            $status->save();
        }

        return response()->json([
            'message' => 'Statut de la gare basculé pour ce parcours.',
            'status'  => $status,
        ]);
    }

    /**
     * (EXISTANT) Retourne tous les statuts de gares par parcours.
     * Utilisé par le frontend pour rafraîchir après un toggle.
     */
    public function getGareStatuses()
    {
        // Si tu veux limiter/structurer différemment, on peut adapter.
        return GareStatus::select('id', 'parcours_id', 'gare_id', 'is_active', 'updated_at', 'created_at')->get();
    }
}