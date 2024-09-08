<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            ['empresa_codigo' => 'E00001',
            'empresa_nome' => "Empresa Matriz Modelo",
            'empresa_cnpj' => "11223344556677",
            'empresa_email' => "empresa.matriz@gmail.com",
            'empresa_tel_celular' => "19998741234",
            'empresa_tel_comercial' => "1936331234",
            'empresa_insc_estadual' => "123456789",
            'empresa_insc_municipal' => "987654321",
            'empresa_nome_logo' => "Empresa Matriz",
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')]
        );

        DB::table('users')->insert(
            ['empresa_codigo' => 'E00002',
            'empresa_nome' => "Empresa Filial Modelo",
            'empresa_cnpj' => "99887766554433",
            'empresa_email' => "empresa.filial@gmail.com",
            'empresa_tel_celular' => "19925839631",
            'empresa_tel_comercial' => "1936339863",
            'empresa_insc_estadual' => "369258147",
            'empresa_insc_municipal' => "741852963",
            'empresa_nome_logo' => "Empresa Filial",
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')]
        );
    }
}