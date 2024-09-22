<?php

namespace App\Services\Contracts;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(array $credentials): ?array;
    //public function register(array $data): User;

   
}
