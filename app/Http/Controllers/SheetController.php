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

    /**
     * Retrieves the sheets associated with the authenticated user.
     *
     * @param Request $request The HTTP request object containing the authenticated user's information.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the user's sheets or an error message on failure.
     */
    public function getUserSheets(Request $request)
    {
        $firebaseUser = $request->attributes->get('firebase_user');

        $getUserSheets = $this->sheetsService->getUserSheets($firebaseUser->uid);

        if($getUserSheets['success'] === false) {
            return response()->json(['error' => 'Erro ao buscar fichas', 'erro' => $getUserSheets['erro']], 401);
        }

        return response()->json(['sheets' => $getUserSheets['sheets']], 200);
    }

    /**
     * Retrieves a sheet by its ID associated with the authenticated user.
     *
     * @param Request $request The HTTP request object containing the authenticated user's information and the sheet ID.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the sheet or an error message on failure.
     */
    public function getSheet(Request $request)
    {
        $id = $request->route('id');

        $firebaseUser = $request->attributes->get('firebase_user');

        $getSheet = $this->sheetsService->getSheet($id, $firebaseUser->uid);

        if($getSheet['success'] === false) {
            return response()->json(['error' => 'erro ao buscar ficha', 'erro' => $getSheet['erro']], 401);
        }

        return response()->json(['sheet' => $getSheet['sheet']], 200);
    }

    /**
     * Creates a new sheet based on the provided request data.
     *
     * Validates the request to ensure 'model_id' and 'data' are provided,
     * retrieves the authenticated user's UID, and constructs a new sheet
     * data structure. Attempts to create the sheet using the SheetsService.
     * If the creation is unsuccessful, returns an error response; otherwise,
     * returns a success response with the created sheet data.
     *
     * @param Request $request The HTTP request object containing the sheet data and authenticated user's information.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the created sheet or an error message on failure.
     */
    public function create(Request $request)
    {
        $request->validate([
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

        if($creatingSheet['success'] === false) {
            return response()->json(['error' => $creatingSheet['message'], 'erro' => $creatingSheet['erro']], 401);
        }

        return response()->json(['message' => 'ficha criada com sucesso', 'sheet' => $creatingSheet['sheet']], 200);
    }

    /**
     * Updates a sheet based on the provided request data.
     *
     * Validates the request to ensure 'id' and 'data' are provided,
     * retrieves the authenticated user's UID, and constructs a new sheet
     * data structure. Attempts to update the sheet using the SheetsService.
     * If the update is unsuccessful, returns an error response; otherwise,
     * returns a success response with the updated sheet data.
     *
     * @param Request $request The HTTP request object containing the sheet data and authenticated user's information.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the updated sheet or an error message on failure.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'data' => 'required'
        ]);

        $firebaseUser = $request->attributes->get('firebase_user');

        $updatingSheet = $this->sheetsService->update($request->id, $firebaseUser->uid, $request->data);

        if($updatingSheet['success'] === false){
            return response()->json(['error' => 'erro ao editar ficha', 'erro' => $updatingSheet['erro']], 401);
        }


        return response()->json(['message' => 'ficha editada com sucesso', 'sheet' => $updatingSheet['updatedSheet']], 200);
    }

    /**
     * Deletes a sheet based on the provided request data.
     *
     * Retrieves the authenticated user's UID and the sheet ID from the route,
     * and attempts to delete the sheet using the SheetsService. If the deletion
     * is unsuccessful, returns an error response; otherwise, returns a success
     * response.
     *
     * @param Request $request The HTTP request object containing the sheet ID and authenticated user's information.
     * @return \Illuminate\Http\JsonResponse A JSON response containing an error message on failure or a success message on success.
     */
    public function delete(Request $request)
    {
        $id = $request->route('id');

        $firebaseUser = $request->attributes->get('firebase_user');

        $deletingSheet = $this->sheetsService->delete($id, $firebaseUser->uid);

        if($deletingSheet['success'] === false){
            return response()->json(['error' => 'erro ao deletar ficha', 'erro' => $deletingSheet['erro']], 401);
        }

        return response()->json(['message' => 'ficha deletada com sucesso'], 200);
    }
}
