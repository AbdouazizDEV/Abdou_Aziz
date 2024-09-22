<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest3 extends FormRequest
{
    public function authorize(): bool
    {
        // Autoriser seulement si l'utilisateur connecté est un CM
        return $this->user()->role_id === 3;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
            'role_id' => 'required|in:5', // CM peut seulement créer des Apprenants
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
