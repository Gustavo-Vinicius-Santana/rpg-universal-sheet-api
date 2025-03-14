<?php

namespace App\Repositories;

use App\Models\Sheet;

class SheetsRepository
{
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

    public function update(int $id, array $data)
    {
        $id = $id;
        $data = $data;

        $updatedSheet = [
            'data' => $data
        ];

        $sheet = Sheet::where('id', $id)->update($updatedSheet);
        if ($sheet == 0) {
            return [
                'success' => false,
                'erro' => 'Ficha nao encontrada'
            ];
        }

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