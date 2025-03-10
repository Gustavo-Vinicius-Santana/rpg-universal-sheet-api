<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AuthService;

class UserController extends Controller
{
    private UserService $userService;
    private AuthService $authService;

    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function index(Request $request)
    {
        $firebaseUser = $this->userService->index($request);

        if ($firebaseUser) {
            return response()->json(['user' => $firebaseUser]);
        }

        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    public function update(Request $request)
    {
        $firebaseUser = $request->attributes->get('firebase_user');

        $updateUser = $this->userService->update($firebaseUser->uid, $request->all());

        if ($updateUser['success'] === false) {
            return response()->json(['error' => $updateUser['message'], 'erro' => $updateUser['erro']], 401);
        }

        return response()->json(['message' => $updateUser['message']], 200);
    }

    public function delete(Request $request)
    {
        $firebaseUser = $request->attributes->get('firebase_user');
        $uid = $firebaseUser->uid;

        $deleteUserFirebase = $this->authService->delete($uid);
        if ($deleteUserFirebase['success'] === false) {
            return response()->json(['errorFireBase' => $deleteUserFirebase['message'], 'erro' => $deleteUserFirebase['erro']], 401);
        }

        $deleteUser = $this->userService->delete($uid);
        if ($deleteUser['success'] === false) {
            return response()->json(['error' => $deleteUser['message']], 401);
        }

        return response()->json(['message' => $deleteUser['message']], 200);
    }
}
