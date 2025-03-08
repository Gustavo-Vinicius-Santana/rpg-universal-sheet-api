<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $firebaseUser = $this->userService->index($request);

        if ($firebaseUser) {
            return response()->json(['user' => $firebaseUser]);
        }

        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }
}
