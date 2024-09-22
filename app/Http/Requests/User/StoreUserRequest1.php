<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest1 extends FormRequest
{
    public function authorize(): bool
    {
        // Autoriser seulement si l'utilisateur connecté est un Admin
        return $this->user()->role_id === 1;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
            'role_id' => 'required|in:1,2,3,4,5', // Admin peut créer Admin, Manager, Coach, CM ou Apprenant
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
