<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        $uid = $data['uid'];
        $firstUserName = $data['first_name'];
        $lastName = $data['last_name'];
        $typeAcount = $data['type_acount'];
        
        $newUser = [
            'uid' => $uid,
            'first_name' => $firstUserName,
            'last_name' => $lastName,
            'type_acount' => $typeAcount
        ];

        return User::create($newUser);
    }

    public function update(String $uid, array $data)
    {
        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuário nao encontrado'
            ];
        }

        $user->update($data);

        return [
            'success' => true,
            'message' => 'Usuário atualizado com sucesso!'
        ];
    }

    public function delete(String $uid)
    {
        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuário nao encontrado'
            ];
        }

        $user->delete();

        return [
            'success' => true,
            'message' => 'Usuário deletado com sucesso'
        ];
    }
}