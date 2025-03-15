<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sistem_rpg_models_sheet', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('system_name')->unique(); // Nome do sistema de RPG
            $table->timestamps();
        });
        
        Schema::create('sheets', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('model_name'); // Nome do modelo
            $table->string('player_uid'); // UID do jogador
            $table->jsonb('data'); // Dados do jogador em JSON
            $table->foreignId('system_id')->constrained('sistem_rpg_models_sheet')->onDelete('cascade'); // FK para sistema de RPG
            $table->timestamps();

            $table->foreign('player_uid')->references('uid')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Remover as tabelas na ordem correta (evita problemas de chave estrangeira)
        Schema::dropIfExists('sistem_rpg_models_sheet');
        Schema::dropIfExists('sheets');
    }
};
