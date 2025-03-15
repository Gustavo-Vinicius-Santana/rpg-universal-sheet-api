<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SistemRpgModelsSheetSeeder extends Seeder
{
    public function run()
    {
        DB::table('sistem_rpg_models_sheet')->insert([
            ['id'=> 1, 'system_name' => 'Dungeons and Dragons 5e'],
            ['id'=> 2,'system_name' => 'ordem paranormal'],
            ['id'=> 3,'system_name' => 'gurps'],
        ]);
    }
}
