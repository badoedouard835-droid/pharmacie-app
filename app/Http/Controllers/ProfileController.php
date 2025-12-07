<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil
     */
    public function index()
    {
        $user = Auth::user();
        return view('profil.index', compact('user'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profil.edit', compact('user'));
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string'],
        ], [
            'name.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
        ]);

        $user->update($validated);
        
        return redirect()->route('profile.index')
            ->with('success', 'Vos informations ont été mises à jour avec succès !');
    }

    /**
     * Uploader une photo de profil
     */
    public function uploadPhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:20448'],
        ], [
            'photo.required' => 'Veuillez sélectionner une photo',
            'photo.image' => 'Le fichier doit être une image',
            'photo.mimes' => 'Formats acceptés : JPEG, PNG, JPG',
            'photo.max' => 'La photo ne doit pas dépasser 20 Mo',
        ]);

        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }

        $path = $request->file('photo')->store('profiles', 'public');

        $user->update(['photo' => $path]);

        return redirect()->route('profile.index')
            ->with('success', 'Votre photo de profil a été mise à jour !');
    }

    /**
     * Supprimer la photo de profil
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
            $user->update(['photo' => null]);
            
            return redirect()->route('profile.index')
                ->with('success', 'Votre photo de profil a été supprimée.');
        }

        return redirect()->route('profile.index')
            ->with('error', 'Aucune photo à supprimer.');
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'L\'ancien mot de passe est obligatoire',
            'password.required' => 'Le nouveau mot de passe est obligatoire',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'L\'ancien mot de passe est incorrect.'
            ])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Votre mot de passe a été changé avec succès !');
    }

    /**
     * Supprimer le compte
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required'],
        ], [
            'password.required' => 'Veuillez saisir votre mot de passe',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Mot de passe incorrect.'
            ]);
        }

        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Votre compte a été supprimé.');
    }
}