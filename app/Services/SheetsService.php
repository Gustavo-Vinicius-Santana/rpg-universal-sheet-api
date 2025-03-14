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