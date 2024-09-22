<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    
    public function isAdmin(User $user)
    {
        return $user->role === 'Admin';
    }

    public function isManager(User $user)
    {
        // dd($user->role);
        return $user->role === 'Manager';
    }
    public function isCoach(User $user){

        return $user->role === 'coach';
    }
    public function isCM(User $user){

        return $user->role === 'CM';
    }
    public function isApprenant(User $user){

        return $user->role === 'Apprenant';
    }

   /*  public function isClient(User $user)
    {
        return $user->role->name === 'client';
    } */
}