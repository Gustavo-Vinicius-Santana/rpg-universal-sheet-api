<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class FirebaseAuthMiddleware
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Pega o token do cabeçalho Authorization
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return response()->json(['message' => 'Token de autenticação não fornecido'], 401);
        }

        // O token vem no formato "Bearer <token>"
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['message' => 'Formato de token inválido'], 401);
        }

        $idToken = $matches[1];

        try {
            // Verifica o token
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);

            // Pega o UID do usuário autenticado
            $uid = $verifiedIdToken->claims()->get('sub');

            // Busca o usuário pelo UID
            $firebaseUser = $this->auth->getUser($uid);

            // Adiciona o usuário ao request para uso nas rotas protegidas
            $request->attributes->add(['firebase_user' => $firebaseUser]);

        } catch (FailedToVerifyToken $e) {
            return response()->json(['message' => 'Token inválido ou expirado', 'error' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}