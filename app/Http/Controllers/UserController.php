<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest1;
use App\Http\Requests\User\StoreUserRequest2;
use App\Http\Requests\User\StoreUserRequest3;
use App\Http\Requests\User\StoreUserRequest4;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
     // Liste des utilisateurs avec filtrage par rôle
     public function index(Request $request)
    {
        $role = null;
        
        // Mapping des rôles aux role_id
        $roles = [
            'admin' => 1,
            'manager' => 2,
            'Coach' => 3,
            'CM' => 4,
            'Apprenant' => 5,
        ];

        // Vérifier si le paramètre role est dans la requête
        if ($request->has('role') && isset($roles[$request->get('role')])) {
                $role = $roles[$request->get('role')];
        }

        // Obtenir les utilisateurs en fonction du rôle
        $users = $this->userService->getAllUsers($role);

        return response()->json($users, 200);
    }



    // Pour un Admin (role_id = 1)
    public function storeAdmin(StoreUserRequest1 $request)
    {
        $data = $request->validated();
        $result = $this->userService->createUser($data);

        return response()->json($result, 201);
    }

    // Pour un Manager (role_id = 2)
    public function storeManager(StoreUserRequest2 $request)
    {
        $data = $request->validated();
        $result = $this->userService->createUser($data);

        return response()->json($result, 201);
    }

    // Pour un CM (role_id = 4)
    public function storeCm(StoreUserRequest3 $request)
    {
        $data = $request->validated();
        $result = $this->userService->createUser($data);

        return response()->json($result, 201);
    }
    public function show($id)
    {
        
        $user = $this->userService->getUserById($id);
        
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
    
        return response()->json($user, 200);
    }
    
    public function update(Request $request, $id)
    {
        $user = $this->userService->getUserById($id);
    
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
                
            // Ajoutez d'autres règles de validation selon vos besoins
        ]);
    
        $user = $this->userService->updateUser($id, $data);
    
        if (!$user) {
            return response()->json(['message' => 'Erreur lors de la mise à jour'], 400);
        }
    
        return response()->json($user, 200);
    }
    
}
