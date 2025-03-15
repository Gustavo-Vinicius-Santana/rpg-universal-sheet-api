<?php

namespace App\Repositories;

use App\Models\SistemRpgModelsSheet;

class ModelRepository
{
    /**
     * Encontra um modelo de ficha de RPG por seu ID.
     *
     * @param int $id O ID do modelo de ficha a ser encontrado.
     *
     * @return SistemRpgModelsSheet|null O modelo de ficha encontrado,
     *                                    ou null se n o for encontrado.
     */
    public function find(int $id)
    {
        return SistemRpgModelsSheet::find($id);
    }
}