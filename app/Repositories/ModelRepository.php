<?php

namespace App\Repositories;

use App\Models\SistemRpgModelsSheet;

class ModelRepository
{
    public function find(int $id)
    {
        return SistemRpgModelsSheet::find($id);
    }
}