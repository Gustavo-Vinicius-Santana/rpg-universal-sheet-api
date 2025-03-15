<?php

namespace App\Services;

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;

class AuthService
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Creates a new user in the Firebase Authentication system.
     * 
     * If the registration is successful, an array with keys 'success', 'user' is returned.
     * The 'success' key will be set to true and the 'user' key will contain the newly created user.
     * 
     * If an EmailExists exception is thrown, an array with keys 'success', 'message', 'erro' is returned.
     * The 'success' key will be set to false, the 'message' key will contain 'E-mail already exists',
     * and the 'erro' key will contain the exception message.
     * 
     * If any other exception is thrown, an array with keys 'success', 'message', 'erro' is returned.
     * The 'success' key will be set to false, the 'message' key will contain 'Error creating user',
     * and the 'erro' key will contain the exception message.
     * 
     * @param string $email The email address of the new user.
     * @param string $password The password of the new user.
     * @return array An array containing the result of the registration.
     */
    public function register($email, $password)
    {
        try{
            $user = $this->auth->createUserWithEmailAndPassword($email, $password);
            return [
                'success' => true,
                'user' => $user
            ];
        }
        catch (EmailExists $e) {
            return [
                'success' => false,
                'message' => 'E-mail already exists',
                'erro' => $e->getMessage()
            ];
        }
        catch (AuthException | FirebaseException $e) {
            return [
                'success' => false,
                'message' => 'Error creating user',
                'erro' => $e->getMessage()
            ];
        }
    }

    /**
     * Log in a user in the Firebase Authentication system.
     * 
     * If the login is successful, an array with keys 'success', 'token' is returned.
     * The 'success' key will be set to true and the 'token' key will contain an ID token for the user.
     * 
     * If an AuthException or FirebaseException is thrown, an array with keys 'success', 'message', 'erro' is returned.
     * The 'success' key will be set to false, the 'message' key will contain 'E-mail or password incorrect',
     * and the 'erro' key will contain the exception message.
     * 
     * @param string $email The email address of the user to log in.
     * @param string $password The password of the user to log in.
     * @return array An array containing the result of the login.
     */
    public function login(String $email, String $password)
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
            $idToken = $signInResult->idToken(); 
        }
        catch (AuthException | FirebaseException $e) {
            return [
                'success' => false,
                'message' => 'E-mail or password incorrect',
                'erro' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
            'token' => $idToken
        ];
    }

    /**
     * Send a password reset email to the user.
     * 
     * If the email is sent successfully, an array with keys 'success', 'message' is returned.
     * The 'success' key will be set to true and the 'message' key will contain 'E-mail de redefini o de senha enviado com sucesso'.
     * 
     * If an AuthException or FirebaseException is thrown, an array with keys 'success', 'message', 'erro' is returned.
     * The 'success' key will be set to false, the 'message' key will contain 'Erro ao atualizar e-mail',
     * and the 'erro' key will contain the exception message.
     * 
     * @param string $email The email address of the user to send the password reset email to.
     * @return array An array containing the result of sending the password reset email.
     */
    public function changePassword(String $email)
    {
        try {
            $this->auth->sendPasswordResetLink($email);
            return [
                'success' => true,
                'message' => 'E-mail de redefinição de senha enviado com sucesso'
            ];
        }
        catch (AuthException | FirebaseException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao atualizar e-mail',
                'erro' => $e->getMessage()
            ];
        }
    }

    /**
     * Change the email of a user in the Firebase Authentication system.
     * 
     * If the email is changed successfully, an array with keys 'success', 'message' is returned.
     * The 'success' key will be set to true and the 'message' key will contain 'E-mail de verificação enviado com sucesso'.
     * 
     * If an AuthException or FirebaseException is thrown, an array with keys 'success', 'message', 'erro' is returned.
     * The 'success' key will be set to false, the 'message' key will contain 'Erro ao atualizar e-mail',
     * and the 'erro' key will contain the exception message.
     * 
     * @param string $uid The uid of the user to change the email.
     * @param string $email The new email address of the user.
     * @return array An array containing the result of changing the email.
     */
    public function changeEmail(String $uid,String $email)
    {
        try {
            $this->auth->changeUserEmail($uid, $email);
            return [
                'success' => true,
                'message' => 'E-mail de verificação enviado com sucesso'
            ];
        }
        catch (AuthException | FirebaseException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao atualizar e-mail',
                'erro' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a user from the Firebase Authentication system.
     * 
     * If the user is deleted successfully, an array with keys 'success', 'message' is returned.
     * The 'success' key will be set to true and the 'message' key will contain 'Usuário deletado com sucesso'.
     * 
     * If an AuthException or FirebaseException is thrown, an array with keys 'success', 'message', 'erro' is returned.
     * The 'success' key will be set to false, the 'message' key will contain 'Erro ao deletar usuário',
     * and the 'erro' key will contain the exception message.
     * 
     * @param string $uid The uid of the user to delete.
     * @return array An array containing the result of deleting the user.
     */
    public function delete(String $uid)
    {
        try {
            $this->auth->deleteUser($uid);
            return [
                'success' => true,
                'message' => 'Usuário deletado com sucesso'
            ];
        }
        catch (AuthException | FirebaseException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao deletar usuário',
                'erro' => $e->getMessage()
            ];
        }
    }
}