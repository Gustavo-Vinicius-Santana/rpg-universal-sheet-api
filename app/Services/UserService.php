<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\AuthService;

class UserService
{
    private AuthService $authService;
    private UserRepository $repository;

    public function __construct(AuthService $authService, UserRepository $repository)
    {
        $this->authService = $authService;
        $this->repository = $repository;
    }

    public function index($request)
    {
        // Obtém o usuário Firebase do middleware
        $firebaseUser = $request->attributes->get('firebase_user');

        return $firebaseUser;
    }

    public function create(array $data)
    {
        $name = $data['first_name'];
        $lastName = $data['last_name'];
        $typeAcount = $data['type_acount'];

        $email = $data['email'];
        $password = $data['password'];

        
        $userFirebase = $this->authService->register($email, $password);

        if ($userFirebase['success'] === false) {
            return $userFirebase['erro'];
        }

        $user = $userFirebase['user'];
        $uid = $user->uid;

        $newUser = [
            'uid' => $uid,
            'first_name' => $name,
            'last_name' => $lastName,
            'type_acount' => $typeAcount
        ];

        return $this->repository->create($newUser);
    }

    public function update(User $user, array $data)
    {
        //
    }

    public function delete(User $user)
    {
        //
    }
}