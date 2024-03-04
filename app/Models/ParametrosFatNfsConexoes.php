<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosFatNfsConexoes extends Model
{
    use HasFactory;

    //protected $primaryKey = 'empresa_nfs';
    
    protected $fillable = [
        'conexao_empresa',
        'conexao_provedor',
        'conexao_usuario',
        'conexao_senha',
        'conexao_token',
        'conexao_wsdl',
        'conexao_ambiente'
     ];
}
