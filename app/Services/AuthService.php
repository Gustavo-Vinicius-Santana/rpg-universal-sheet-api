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
}