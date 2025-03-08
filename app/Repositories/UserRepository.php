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

    public function update(User $user, array $data)
    {
        //
    }

    public function delete(User $user)
    {
        //
    }
}