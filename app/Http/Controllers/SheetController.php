<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SheetsService;

class SheetController extends Controller
{
    protected SheetsService $sheetsService;

    public function __construct(SheetsService $sheetsService)
    {
        $this->sheetsService = $sheetsService;
    }

    public function getUserSheets(Request $request)
    {
        $firebaseUser = $request->attributes->get('firebase_user');

        $getUserSheets = $this->sheetsService->getUserSheets($firebaseUser->uid);

        if($getUserSheets['success'] === false) {
            return response()->json(['error' => 'Erro ao buscar fichas', 'erro' => $getUserSheets['erro']], 401);
        }

        return response()->json(['sheets' => $getUserSheets['sheets']], 200);
    }

    public function getSheet(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required'
        ]);

        $firebaseUser = $request->attributes->get('firebase_user');

        $getSheet = $this->sheetsService->getSheet($request->id, $firebaseUser->uid);

        if($getSheet['success'] === false) {
            return response()->json(['error' => 'erro ao buscar ficha', 'erro' => $getSheet['erro']], 401);
        }

        return response()->json(['sheet' => $getSheet['sheet']], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'model_name' => 'required',
            'model_id' => 'required',
            'data' => 'required'
        ]);

        $firebaseUser = $request->attributes->get('firebase_user');

        $newSheet = [
            'model_name' => $request->model_name,
            'model_id' => $request->model_id,
            'user_id' => $firebaseUser->uid,
            'data' => $request->data
        ];

        $creatingSheet = $this->sheetsService->create($newSheet);
        dd($creatingSheet);

        if($creatingSheet['success'] === false) {
            return response()->json(['error' => $creatingSheet['message'], 'erro' => $creatingSheet['erro']], 401);
        }

        return response()->json(['message' => 'ficha criada com sucesso', 'sheet' => $creatingSheet['sheet']], 200);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'data' => 'required'
        ]);

        $updatingSheet = $this->sheetsService->update($request->id, $request->data);

        if($updatingSheet['success'] === false){
            return response()->json(['error' => 'erro ao editar ficha', 'erro' => $updatingSheet['erro']], 401);
        }


        return response()->json(['message' => 'ficha editada com sucesso', 'sheet' => $updatingSheet['updatedSheet']], 200);
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required'
        ]);

        $deletingSheet = $this->sheetsService->delete($request->id);

        if($deletingSheet['success'] === false){
            return response()->json(['error' => 'erro ao deletar ficha', 'erro' => $deletingSheet['erro']], 401);
        }

        return response()->json(['message' => 'ficha deletada com sucesso'], 200);
    }
}
