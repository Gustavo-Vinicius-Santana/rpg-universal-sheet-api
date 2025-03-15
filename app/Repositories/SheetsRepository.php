<?php

namespace App\Repositories;

use App\Models\Sheet;

class SheetsRepository
{
    /**
     * Retrieves a sheet by its ID and user UID.
     *
     * @param int $id The ID of the sheet to retrieve.
     * @param String $uid The UID of the user associated with the sheet.
     * @return array An associative array containing the success status and the sheet data or an error message.
     */

    public function getSheet(int $id, String $uid)
    {
        $sheet = Sheet::where('id', $id)->where('player_uid', $uid)->first();

        try{
            return [
                'success' => true,
                'sheet' => $sheet ? $sheet->toArray() : null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'erro' => $e->getMessage()
            ];
        }
    }

    /**
     * Retrieves all sheets associated with the given user UID.
     *
     * @param String $uid The UID of the user to retrieve sheets for.
     * @return array An associative array containing the success status and the sheets data or an error message.
     */
    public function getUserSheets(String $uid)
    {
        try{
            return [
                'success' => true,
                'sheets' => Sheet::where('player_uid', $uid)->get()->toArray()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'erro' => $e->getMessage()
            ];
        }
    }

    /**
     * Creates a new sheet for the given user with the given data.
     *
     * @param array $data An associative array containing the user's UID, model name, model system ID, and sheet data.
     * @return array An associative array containing the success status and the created sheet data or an error message.
     */
    public function create( array $data)
    {
        $uid = $data['uid'];
        $modelName = $data['model_name'];
        $modelSystem = $data['model_id'];
        $data = $data['data'];

        $newSheet = [
            'player_uid' => $uid,
            'model_name' => $modelName,
            'system_id' => $modelSystem,
            'data' => $data
        ];

        try{
            $sheet = Sheet::create($newSheet);
            return $sheet;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'erro' => $e->getMessage()
            ];
        }
    }

    /**
     * Updates a sheet with the given ID and data.
     *
     * @param int $id The ID of the sheet to update.
     * @param array $data An associative array containing the sheet data to update.
     * @return array An associative array containing the success status and the updated sheet data or an error message.
     */
    public function update(int $id, array $data)
    {
        $id = $id;
        $data = $data;

        $updatedSheet = [
            'data' => $data
        ];

        try{
            Sheet::where('id', $id)->update($updatedSheet);
            return [
                'success' => true,
                'updatedSheet' => sheet::find($id)->toArray()
            ];
        } catch (\Exception $e){
            return [
                'success' => false,
                'erro' => $e->getMessage()
            ];
        }
        
    }

    /**
     * Deletes a sheet with the given ID.
     *
     * @param String $id The ID of the sheet to delete.
     * @return array An associative array containing the success status and a message indicating the result of the deletion or an error message.
     */
    public function delete(String $id)
    {
        try{
            Sheet::where('id', $id)->delete();
            return [
                'success' => true,
                'deletingSheet' => 'deletado com sucesso'
            ];
        } catch(\Exception $e){
            return [
                'success' => false,
                'erro' => $e->getMessage()
            ];
        }
        
    }
}