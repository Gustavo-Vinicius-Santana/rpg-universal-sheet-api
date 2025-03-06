<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;

class FirebaseAuthController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        try {
            $user = $this->auth->createUserWithEmailAndPassword($request->email, $request->password);
            return response()->json(['message' => 'Usuário criado com sucesso!', 'user' => $user], 201);
        } catch (EmailExists $e) {
            return response()->json(['error' => 'E-mail já cadastrado!'], 400);
        } catch (AuthException | FirebaseException $e) {
            return response()->json(['error' => 'Erro ao criar usuário', 'erro' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $idToken = $signInResult->idToken(); // Token JWT do Firebase

            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'token' => $idToken
            ]);
        } catch (AuthException | FirebaseException $e) {
            return response()->json(['error' => 'E-mail ou senha incorretos'], 401);
        }
    }
}