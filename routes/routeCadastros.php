<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Área de Cadastros do Sistema
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas no cadastramento de informações de empresa, clientes, usuarios, bancos e produtos entre outros.
|
*/

// -------------------- Cliente -------------------- //

/* ********** Rotas do Cadastro de Clientes - Rotas REST ********** */
Route::resource('/cadastros/cliente/cadastroCliente', App\Http\Controllers\Cadastros\Cliente\CadastroClienteController::class);

/* ********** Rotas do Cadastro de Endereços do Cliente - Rotas REST ********** */
Route::resource('/cadastros/cliente/clienteEndereco', App\Http\Controllers\Cadastros\Cliente\CadastroClienteEnderecoController::class);

// -------------------- Usuário -------------------- //

/* ********** Rotas do Cadastro de Usuários - Rotas REST ********** */
Route::resource('/cadastros/usuario/cadastroUsuario', App\Http\Controllers\Cadastros\Usuario\CadastroUsuarioController::class);

/* ********** Rotas do Cadastro de Endereços do Usuário - Rotas REST ********** */
Route::resource('/cadastros/usuario/usuarioEndereco', App\Http\Controllers\Cadastros\Usuario\CadastroUsuarioEnderecoController::class);

// -------------------- Empresa -------------------- //

/* ********** Rotas do Cadastro de Empresas - Rotas REST ********** */
Route::resource('/cadastros/empresa/cadastroEmpresa', App\Http\Controllers\Cadastros\Empresa\CadastroEmpresaController::class);

/* ********** Rotas do Cadastro de Endereços da Empresa - Rotas REST ********** */
Route::resource('/cadastros/empresa/empresaEndereco', App\Http\Controllers\Cadastros\Empresa\CadastroEmpresaEnderecoController::class);

// -------------------- Prestador -------------------- //

/* ********** Rotas do Cadastro de Prestador - Rotas REST ********** */
Route::resource('/cadastros/prestador/cadastroPrestador', App\Http\Controllers\Cadastros\Prestador\CadastroPrestadoresController::class);

/* ********** Rotas do Cadastro de Endereços do Prestador - Rotas REST ********** */
Route::resource('/cadastros/prestador/prestadorEndereco', App\Http\Controllers\Cadastros\Prestador\CadastroPrestadoresEnderecoController::class);

// -------------------- Administradora de Cartão -------------------- //

/* ********** Rotas do Cadastro de Administradora de Cartão - Rotas REST ********** */
Route::resource('/cadastros/cartao/cadastroCartao', App\Http\Controllers\Cadastros\Cartao\CadastroCartaoAdministradoraController::class);