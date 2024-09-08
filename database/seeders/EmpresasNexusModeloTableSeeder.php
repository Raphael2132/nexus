<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmpresasNexusModeloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cadastro_empresas')->insert(
            ['empresa_codigo' => 'E00001',
            'empresa_nome' => "Empresa Matriz Modelo",
            'empresa_cnpj' => "12345678912345",
            'empresa_email' => "empresa.matriz@gmail.com",
            'empresa_tel_celular' => "19998741234",
            'empresa_tel_comercial' => "1936331234",
            'empresa_insc_estadual' => "852963741",
            'empresa_insc_municipal' => "963258741",
            'empresa_nome_logo' => "Empresa Matriz",
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')]
        );
    }
}