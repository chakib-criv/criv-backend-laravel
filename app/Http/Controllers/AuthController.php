<?php

namespace App\Http\Controllers;

use App\Models\User; // Importe le modèle User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Pour hasher les mots de passe
use Illuminate\Validation\ValidationException; // Pour les erreurs de validation
use Illuminate\Auth\AuthenticationException; // Pour les erreurs d'authentification

class AuthController extends Controller
{
    /**
     * Gérer l'inscription d'un nouvel utilisateur.
     */
    public function register(Request $request)
    {
        try {
            // Valide les données de la requête
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' vérifie que 'password_confirmation' correspond
            ]);

            // Crée un nouvel utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hache le mot de passe pour la sécurité
                'role' => 'user', // Définit le rôle par défaut 'user' lors de l'inscription
            ]);

            // Crée un jeton d'authentification pour le nouvel utilisateur
            $token = $user->createToken('auth_token')->plainTextToken;

            // Retourne la réponse avec l'utilisateur et le jeton
            return response()->json([
                'message' => 'Utilisateur enregistré avec succès.',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 201); // Code 201 pour Créé

        } catch (ValidationException $e) {
            // Gère les erreurs de validation
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422); // Code 422 pour Entité non traitable
        } catch (\Exception $e) {
            // Gère d'autres erreurs inattendues
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'inscription.',
                'error' => $e->getMessage()
            ], 500); // Code 500 pour Erreur interne du serveur
        }
    }

    /**
     * Gérer la connexion d'un utilisateur existant.
     */
    public function login(Request $request)
    {
        try {
            // Valide les identifiants
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            // Tente de trouver l'utilisateur par email
            $user = User::where('email', $request->email)->first();

            // Vérifie si l'utilisateur existe et si le mot de passe est correct
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw new AuthenticationException('Identifiants invalides.');
            }

            // Supprime les jetons existants pour éviter une accumulation
            $user->tokens()->delete();

            // Crée un nouveau jeton pour l'utilisateur
            $token = $user->createToken('auth_token')->plainTextToken;

            // Retourne la réponse avec l'utilisateur et le jeton
            return response()->json([
                'message' => 'Connexion réussie.',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200); // Code 200 pour OK

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (AuthenticationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 401); // Code 401 pour Non autorisé
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la connexion.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gérer la déconnexion de l'utilisateur (révoque le jeton actuel).
     */
    public function logout(Request $request)
    {
        // Supprime le jeton actuel de l'utilisateur connecté
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie.'
        ], 200);
    }
}