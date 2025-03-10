<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    use HasFactory;

    protected $fillable = ['model_name', 'player_uid', 'data', 'system_id'];

    protected $casts = [
        'data' => 'array',
    ];

    public function system()
    {
        return $this->belongsTo(SistemRpgModelsSheet::class, 'system_id');
    }
}
