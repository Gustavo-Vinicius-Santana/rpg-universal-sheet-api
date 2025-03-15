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

    /**
     * Relacionamento com o sistema de RPG.
     */
    public function system()
    {
        return $this->belongsTo(SistemRpgModelsSheet::class, 'system_id');
    }

    /**
     * Relacionamento com o usuário (Firebase).
     */
    public function player()
    {
        return $this->belongsTo(User::class, 'player_uid', 'player_uid');
    }
}