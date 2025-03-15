<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Create a new user record in the database.
     *
     * This method accepts an array of user data, extracts necessary fields,
     * and utilizes the User model to create a new user entry in the database.
     *
     * @param array $data An associative array containing user information
     *                    with keys 'uid', 'first_name', 'last_name', and 'type_acount'.
     * @return \Illuminate\Database\Eloquent\Model The newly created user model instance.
     */
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

    /**
     * Updates a user record in the database.
     *
     * This method accepts a user UID and an associative array of user data to update.
     * It retrieves the user record from the database, updates the fields provided in the array,
     * and persists the changes to the database.
     *
     * @param String $uid The unique identifier associated with the user record.
     * @param array $data An associative array containing the user information to update.
     * @return array An associative array containing the success status and a message indicating the result of the update.
     */
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

    /**
     * Deletes a user record in the database.
     *
     * This method accepts a user UID, retrieves the associated user record from the database,
     * and deletes the record. If the user record is not found, an error response is returned.
     *
     * @param String $uid The unique identifier associated with the user record to delete.
     * @return array An associative array containing the success status and a message indicating the result of the deletion.
     */
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