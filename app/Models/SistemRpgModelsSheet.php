<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SistemRpgModelsSheet extends Model
{
    use HasFactory;
    protected $table = 'sistem_rpg_models_sheet';
    protected $fillable = ['system_name'];

    public function sheets()
    {
        return $this->hasMany(Sheet::class, 'system_id');
    }
}
