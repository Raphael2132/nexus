<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosNexusModeloMfTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            ['name' => 'Usuário Master',
            'email' => 'master@gmail.com',
            'password' => Hash::make('123456789'),
            'usuario_codigo' => 'MASTER',
            'usuario_status' => 'A',
            'usuario_tipo' => 'M',
            'usuario_tipo_email' => 'C',
            'usuario_altera_permissoes_acesso' => 'S',
            'usuario_acesso_pararametros' => 'S',
            'usuario_acesso_cadastros' => 'S',
            'usuario_aut_desc' => 'S',
            'usuario_empresa' => 'E00001',
            'usuario_acesso_mod_servicos' => 'S',
            'usuario_acesso_mod_nf' => 'S',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')]
        );

        /*
        DB::table('users')->insert(
            ['name' => 'Usuário Master Filial',
            'email' => 'master.filial1@gmail.com',
            'password' => Hash::make('123456789'),
            'usuario_codigo' => 'MSFL01',
            'usuario_status' => 'A',
            'usuario_tipo' => 'M',
            'usuario_tipo_email' => 'C',
            'usuario_altera_permissoes_acesso' => 'S',
            'usuario_acesso_pararametros' => 'S',
            'usuario_acesso_cadastros' => 'S',
            'usuario_aut_desc' => 'S',
            'usuario_empresa' => 'E00002',
            'usuario_acesso_mod_servicos' => 'S',
            'usuario_acesso_mod_nf' => 'S',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')]
        );
        */
    }
}