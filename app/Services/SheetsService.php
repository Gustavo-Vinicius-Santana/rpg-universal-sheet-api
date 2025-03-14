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

    public function create(array $data)
    {
        $newSheet = [
            'uid' => $data['user_id'],
            'model_name' => $data['model_name'],
            'model_id' => $data['model_id'],
            'data' => $data['data']
        ];

        $checkModel = $this->modelRepository->find($data['model_id']);
        
        if($checkModel == null){
            return [
                'success' => false,
                'erro' => 'Modelo de ficha nao encontrado'
            ];
        }

        $creatingSheet = $this->sheetsRepository->create($newSheet);

        if($creatingSheet['success'] === false) {
            return $creatingSheet['erro'];
        }

        $sheet = [
            'model_name' => $creatingSheet['model_name'],
            'model_id' => $creatingSheet['system_id'],
            'data' => $creatingSheet['data']
        ];

        return [
            'success' => true,
            'sheet' => $sheet
        ];
    }

    public function update(int $id, array $data)
    {
        $updatingSheet = $this->sheetsRepository->update($id, $data);

        if($updatingSheet['success'] === false){
            return $updatingSheet['erro'];
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

    public function delete(String $uid)
    {
        $deletingSheet = $this->sheetsRepository->delete($uid);

        if($deletingSheet['success'] === false){
            return $deletingSheet['erro'];
        }

        return [
            'success' => true,
            'message' => 'ficha deletada com sucesso'
        ];
    }
}