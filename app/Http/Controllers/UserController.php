<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Obtém o usuário Firebase do middleware
        $firebaseUser = $request->attributes->get('firebase_user');

        if ($firebaseUser) {
            return response()->json(['user' => $firebaseUser]);
        }

        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }
}
