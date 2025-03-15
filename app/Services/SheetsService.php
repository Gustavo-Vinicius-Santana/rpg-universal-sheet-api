<?php

namespace App\Services;

use App\Repositories\SheetsRepository;
use App\Repositories\ModelRepository;

class SheetsService
{
    private $sheetsRepository;
    private $modelRepository;

    public function __construct(SheetsRepository $sheetsRepository, ModelRepository $modelRepository)
    {
        $this->sheetsRepository = $sheetsRepository;
        $this->modelRepository = $modelRepository;
    }

    /**
     * Retrieves all RPG sheets associated with a user by their UID.
     *
     * @param String $uid The UID of the user to retrieve.
     *
     * @return array An array containing a 'success' key with a boolean value
     *               indicating the success of the operation, and a 'sheets' key
     *               with an array of objects containing the retrieved RPG sheets,
     *               except for the fields 'player_uid', 'created_at', and 'updated_at'.
     */
    public function getUserSheets(String $uid)
    {
        $seekingSheets = $this->sheetsRepository->getUserSheets($uid);

        if ($seekingSheets['success'] === false) {
            return $seekingSheets['erro'];
        }

        $filteredSheets = collect($seekingSheets['sheets'])->map(function ($ficha) {
            return collect($ficha)->except(['player_uid', 'created_at', 'updated_at']);
        });

        return [
            'success' => true,
            'sheets' => $filteredSheets
        ];
    }

    /**
     * Retrieves an RPG sheet by its ID and the UID of the owning user.
     *
     * @param int $id The ID of the sheet to retrieve.
     * @param String $uid The UID of the user who owns the sheet.
     *
     * @return array An array containing a 'success' key with a boolean value
     *               indicating the success of the operation, and a 'sheet' key
     *               with an object containing the retrieved RPG sheet information,
     *               except for the fields 'player_uid', 'created_at', and 'updated_at'.
     */
    public function getSheet(int $id, String $uid)
    {
        $seekingSheet = $this->sheetsRepository->getSheet($id, $uid);

        if ($seekingSheet['success'] === false) {
            return [
                'success' => false,
                'message' => 'Erro no banco de dados',
                'erro' => $seekingSheet['erro']
            ];
        }

        if ($seekingSheet['sheet'] === null) {
            return [
                'success' => false,
                'erro' => 'Ficha não encontrada'
            ];
        }

        $dataSheet = $seekingSheet['sheet'];

        $sheet = [
            'model_name' => $dataSheet['model_name'],
            'model_id' => $dataSheet['system_id'],
            'data' => $dataSheet['data']
        ];

        return [
            'success' => true,
            'sheet' => $sheet
        ];
    }

    /**
     * Creates a new RPG sheet based on the provided data.
     *
     * Checks if the given RPG sheet model exists and, if so,
     * creates a new sheet with the provided data. If the model does not exist,
     * returns an array with a 'success' key set to false and an 'error' key
     * containing an error message.
     *
     * @param array $data An associative array containing the information
     *                    of the sheet to be created. It must contain the keys
     *                    'user_id', 'model_id', and 'data'.
     *
     * @return array An array containing a 'success' key with a boolean value
     *               indicating the success of the operation, and a 'sheet' key
     *               with an object containing the information of the created RPG sheet.
     */
    public function create(array $data)
    {
        $checkModel = $this->modelRepository->find($data['model_id']);
        
        if($checkModel == null){
            return [
                'success' => false,
                'message' => 'erro no cadastro',
                'erro' => 'Modelo de ficha nao encontrado'
            ];
        }

        $modelName = $checkModel->toArray()['system_name'];

        $newSheet = [
            'uid' => $data['user_id'],
            'model_name' => $modelName,
            'model_id' => $data['model_id'],
            'data' => $data['data']
        ];

        $creatingSheet = $this->sheetsRepository->create($newSheet);

        if($creatingSheet['success'] === false) {
            return [
                'success' => false,
                'message' => 'Erro no cadstro',
                'erro' => $creatingSheet['erro']
            ];
        }

        $sheet = [
            'model_name' => $modelName,
            'model_id' => $creatingSheet['system_id'],
            'data' => $creatingSheet['data']
        ];

        return [
            'success' => true,
            'sheet' => $sheet
        ];
    }

    /**
     * Updates an existing RPG sheet with the provided data.
     *
     * Checks if the sheet exists for the given ID and user UID.
     * If the sheet is found, it attempts to update the sheet with
     * the new data provided. Returns a success response with the
     * updated sheet data or an error message if the update fails.
     *
     * @param int $id The ID of the sheet to update.
     * @param String $uid The UID of the user who owns the sheet.
     * @param array $data An associative array containing the updated sheet data.
     *
     * @return array An associative array containing the success status
     *               and the updated sheet data or an error message.
     */

    public function update(int $id, String $uid, array $data)
    {
        $checkSheet = $this->sheetsRepository->getSheet($id, $uid);

        if($checkSheet['sheet'] === null){
            return [
                'success' => false,
                'erro' => 'ficha nao encontrada'
            ];
        }

        $updatingSheet = $this->sheetsRepository->update($id, $data);

        if($updatingSheet['success'] === false){
            return [
                'success' => false,
                'erro' => $updatingSheet['erro']
            ];
        }

        $sheet = [
            'model_name' => $updatingSheet['updatedSheet']['model_name'],
            'model_id' => $updatingSheet['updatedSheet']['system_id'],
            'data' => $updatingSheet['updatedSheet']['data']
        ];

        return [
            'success' => true,
            'updatedSheet' => $sheet
        ];
    }

    /**
     * Deletes an existing RPG sheet with the given ID and user UID.
     *
     * Checks if the sheet exists for the given ID and user UID.
     * If the sheet is found, it attempts to delete the sheet. Returns a success
     * response with a message indicating the result of the deletion or an
     * error message if the deletion fails.
     *
     * @param String $id The ID of the sheet to delete.
     * @param String $uid The UID of the user who owns the sheet.
     *
     * @return array An associative array containing the success status and a
     *               message indicating the result of the deletion or an error
     *               message.
     */
    public function delete(String $id, String $uid)
    {
        $checkSheet = $this->sheetsRepository->getSheet($id, $uid);

        if($checkSheet['sheet'] === null){
            return [
                'success' => false,
                'erro' => 'ficha nao encontrada'
            ];
        }

        $deletingSheet = $this->sheetsRepository->delete($id);

        if($deletingSheet['success'] === false){
            return [
                'success' => false,
                'erro' => $deletingSheet['erro']
            ];
        }

        return [
            'success' => true,
            'message' => 'ficha deletada com sucesso'
        ];
    }
}