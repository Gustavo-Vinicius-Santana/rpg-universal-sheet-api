<?php

namespace App\Repositories;

use App\Models\SistemRpgModelsSheet;

class ModelRepository
{
    /**
     * Finds an RPG sheet model by its ID.
     *
     * @param int $id The ID of the sheet model to be found.
     *
     * @return SistemRpgModelsSheet|null The found sheet model,
     *                                    or null if not found.
     */
    public function find(int $id)
    {
        return SistemRpgModelsSheet::find($id);
    }
}