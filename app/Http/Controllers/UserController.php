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

    /**
     * Displays the authenticated Firebase user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $firebaseUser = $this->userService->index($request);

        if ($firebaseUser) {
            return response()->json(['user' => $firebaseUser]);
        }

        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    /**
     * Updates the authenticated Firebase user based on the data received in the request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $firebaseUser = $request->attributes->get('firebase_user');

        $updateUser = $this->userService->update($firebaseUser->uid, $request->all());

        if ($updateUser['success'] === false) {
            return response()->json(['error' => $updateUser['message'], 'erro' => $updateUser['erro']], 401);
        }

        return response()->json(['message' => $updateUser['message']], 200);
    }

    /**
     * Deletes the authenticated Firebase user.
     *
     * This method retrieves the authenticated user's UID from the request attributes
     * and attempts to delete the user from both Firebase Authentication and the local
     * database. If the deletion from Firebase fails, an error response is returned.
     * If the local database deletion fails, an error response is returned as well.
     * On successful deletion from both systems, a success message is returned.
     *
     * @param Request $request The HTTP request object containing the authenticated user's information.
     * @return \Illuminate\Http\JsonResponse A JSON response containing a success message on successful deletion or an error message on failure.
     */
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
