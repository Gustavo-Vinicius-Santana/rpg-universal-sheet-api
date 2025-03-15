<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use App\Services\UserService;
use App\Services\AuthService;

class FirebaseAuthController extends Controller
{
    protected $auth;
    protected $userService;
    protected $authService;

    public function __construct(Auth $auth, UserService $userService, AuthService $authService)
    {
        $this->auth = $auth;
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * Create a new user in the Firebase Authentication
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'type_acount' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $newUser = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'type_acount' => $request->type_acount
        ];

        try {
            $newUser = $this->userService->create($request->all());
            return response()->json(['message' => 'Usuário criado com sucesso!', 'user' => $newUser], 201);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar usuário', 'erro' => $e->getMessage()], 500);
        }
    }

    /**
     * Log in a user using Firebase Authentication
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $signInResult = $this->authService->login($request->email, $request->password);

        if ($signInResult['success'] === false) {
            return response()->json(['error' => $signInResult['message'], 'erro' => $signInResult['erro']], 401);
        }

        return response()->json(['token' => $signInResult['token']], 200);
    }

    /**
     * Change the password of the user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $userEmail = $request->email;

        $resultEmail = $this->authService->changePassword($userEmail);

        if ($resultEmail['success'] === false) {
            return response()->json(['error' => $resultEmail['message'], 'erro' => $resultEmail['erro']], 401);
        }

        return response()->json(['message' => $resultEmail['message']], 200);
    }

/**
 * Change the email of the authenticated user
 *
 * This method validates the new email provided by the request, retrieves
 * the authenticated user's UID from the request attributes, and attempts
 * to update the user's email in Firebase Authentication. If the update
 * is successful, a success message is returned. If the update fails or
 * if the user is not authenticated, an error message is returned.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
    public function changeEmail(Request $request)
    {
        $request->validate([
            'newEmail' => 'required|email',
        ]);

        $userEmail = $request->newEmail;
        $firebaseUser = $request->attributes->get('firebase_user');

        if ($firebaseUser) {
            $uid = $firebaseUser->uid;

            $resultEmail = $this->authService->changeEmail($uid, $userEmail);

            if ($resultEmail['success'] === false) {
                return response()->json(['error' => $resultEmail['message'], 'erro' => $resultEmail['erro']], 401);
            }

            return response()->json(['message' => $resultEmail['message']], 200);
        }

        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }
}