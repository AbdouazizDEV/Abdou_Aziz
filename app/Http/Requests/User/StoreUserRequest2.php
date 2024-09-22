<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest2 extends FormRequest
{
    public function authorize(): bool
    {
        // Autoriser seulement si l'utilisateur connecté est un Manager
        return $this->user()->role_id === 2;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
            'role_id' => 'required|in:2,3,4,5', // Manager peut créer Manager, Coach, CM ou Apprenant
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
