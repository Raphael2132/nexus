<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use stdClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Facades\Modulos;
use App\Http\Helpers\Helper;

class CadastroUsuarioController extends Controller
{
    protected $usuario;
    
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Consulta de Usuários vindas das Small Box de Filtro
    |----------------------------------------------------------------------------------------------------
    */
    public function usuarios($tipo)
    {
        //Definimos a empresa que terá os dados exibidos pela selecionada para exibição
        $empresa = session('glo_empresa_exibicao_home');

        //O usuário MASTER é exibido apenas para ele
        if(Auth::user()->usuario_tipo != 'M'){

            if($tipo == 'T'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_tipo','<>','M')->get();
            }elseif($tipo == 'A'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_status','A')->where('usuario_tipo','<>','M')->get();
            }elseif($tipo == 'D'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_status','D')->where('usuario_tipo','<>','M')->get();
            }elseif($tipo == 'ADM'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_tipo','ADM')->get();
            }elseif($tipo == 'PR'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_tipo','PR')->get();
            }else{
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_tipo','CO')->get();
            }

        }else{

            if($tipo == 'T'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->get();
            }elseif($tipo == 'A'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_status','A')->get();
            }elseif($tipo == 'D'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_status','D')->get();
            }elseif($tipo == 'ADM'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_tipo','ADM')->get();
            }elseif($tipo == 'PR'){
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_tipo','PR')->get();
            }else{
                $usuarios = $this->usuario->where('usuario_empresa', $empresa)->where('usuario_tipo','CO')->get();
            }
        }

        return view('/cadastros/usuario/usuarios',['usuarios'=>$usuarios,'tipo'=>$tipo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Aplicação de Cadastro de Usuário
    |----------------------------------------------------------------------------------------------------
    */
    public function cadastro($tipo)
    {
        return view('/cadastros/usuario/cadastroUsuarios',['tipo' => $tipo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Home do Cadastro de Usuários
    |----------------------------------------------------------------------------------------------------
    */
    public function create(){
        return view('home.usuarios');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção do Cadastro do Usuário
    |----------------------------------------------------------------------------------------------------
    */
    public function editar($dadosUsuario, $tipo)
    {
        //Apenas o Usuario MASTER pode editar o usuario MASTER
        if(Auth::user()->usuario_codigo != 'MASTER' && $dadosUsuario == 'MASTER'){
            return redirect()->back()->with('error', 'Apenas o usuário MASTER tem permição de acessar os dados do Usuário Master do sistema!');
        }

        $resultadoUsuario = $this->usuario->where('usuario_codigo',$dadosUsuario)->get();

        return view('/cadastros/usuario/editarCadastroUsuario',['dadosUsuario'=>$resultadoUsuario, 'tipo' => $tipo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Insere os Dados do Novo Usuário
    |----------------------------------------------------------------------------------------------------
    */
    public function inserir(Request $request, $tipo){

        //Inicio do tratamento da quantidade de Usuários permitida para a Empresa
        //$modulos = Modulos::getModulos();
        $modulos = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $request->empresa)->first();
        $qtdTotUsu = $modulos->modulo_qtd_usuarios + $modulos->modulo_qtd_usuarios_ext;

        $qtdUser = DB::table('users')->where('usuario_tipo', '<>', 'M')->where('usuario_status', 'A')->where('usuario_empresa', $request->empresa)->count();
        
        //Não podemos permitir cadastrar um novo usuário se o limite da empresa já estiver atingida
        if($qtdUser >= $qtdTotUsu){

            $dadosEmp = Helper::buscaDadosEmpresa($request->empresa);

            return redirect()->back()->with('info', 'A quantidade máxima de Usuários ativos permitida para a empresa '.$request->empresa.' - '.$dadosEmp->empresa_nome.' já foi atingida! </br></br>A quantidade permitida é de até '.$qtdTotUsu.' usuários ativos, para adquirir mais usuários entre em contato com o nosso atendimento!');
        }

        //Verifica se existe o email cadastrado por que não pode repetir
        $qtdEmail = DB::table('users')->where('email', $request->email)->count();

        if($qtdEmail > 0){
            return redirect()->back()->with('error', 'O Email informado já foi cadastrado em outro usuário!');
        }

        //Verifica o Tipo de Usuário para montagem do código
        if($request->tipoUsuario == 'ADM'){

            $nextval=DB::select("SELECT nextval('sq_cad_usuarios')")[0]->nextval;
            $codigo = 'ADM'.str_pad($nextval,3,'0',STR_PAD_LEFT);

        }elseif($request->tipoUsuario == 'PR'){

            $nextval=DB::select("SELECT nextval('sq_cad_usuarios_pr')")[0]->nextval;
            $codigo = 'PR'.str_pad($nextval,4,'0',STR_PAD_LEFT);

        }elseif($request->tipoUsuario == 'CO'){

            $nextval=DB::select("SELECT nextval('sq_cad_usuarios_co')")[0]->nextval;
            $codigo = 'CO'.str_pad($nextval,4,'0',STR_PAD_LEFT);

        }else{

            $nextval=DB::select("SELECT nextval('sq_cad_usuarios_cx')")[0]->nextval;
            $codigo = 'CX'.str_pad($nextval,4,'0',STR_PAD_LEFT);

        }

        $dados = [
            'name' => $request->nome,
            'email' => $request->email,
            'password' => $request->senha,
            'usuario_codigo' => $codigo,
            'usuario_status' => $request->statusUsuario,
            'usuario_tipo' => $request->tipoUsuario,
            'usuario_tipo_email' => $request->tipoEmail,
            'usuario_empresa' => $request->empresa
        ];
        
        User::create($dados);
        
        return redirect(route('usuario.editarCadastro', ['dadosUsuario' => $codigo, 'tipo' => $tipo]))->with('success', 'Usuário cadastrado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Exclui os Dados do Usuário
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(User $usuario){

        //Não podemos permitir a exclusão de um usuário Master
        if($usuario->usuario_tipo == 'M'){
            return redirect()->back()->with('error', 'Usuário MASTER não pode ser excluído!');
        }

        $usuario->delete();
        
        return redirect(route('home.usuarios'))->with('success', 'Usuário excluído com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Atualização dos Dados do Usuário
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, $usuario, $usuario_cod, $atualiza, $tipo){

        //Verifica a Aba de Dados Atualizada
        if($atualiza == 'dados'){

            //Não podemos permitir a ativação de um usuário se o limite da empresa foi atingido
            if($request->statusUsuario == 'A'){

                //Verificamos o antigo status do usuário para confirmar se ele estava desativado
                $stsUsu = DB::table('users')->where('usuario_codigo', $usuario_cod)->where('usuario_empresa', $request->empUsuario)->first();

                if($stsUsu->usuario_status == "D"){

                    //$modulos = Modulos::getModulos();
                    $modulos = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $request->empUsuario)->first();
                    $qtdTotUsu = $modulos->modulo_qtd_usuarios + $modulos->modulo_qtd_usuarios_ext;

                    $qtdUser = DB::table('users')->where('usuario_tipo', '<>', 'M')->where('usuario_status', 'A')->where('usuario_empresa', $request->empUsuario)->count();
                    
                    //Não permitir cadastrar usuario se ja tiver cadastrado a quantidade limite da empresa
                    if($qtdUser >= $qtdTotUsu){

                        $dadosEmp = Helper::buscaDadosEmpresa($request->empUsuario);

                        return redirect()->back()->with('info', 'A quantidade máxima de Usuários ativos permitida para a empresa '.$request->empUsuario.' - '.$dadosEmp->empresa_nome.' já foi atingida! </br></br>A quantidade permitida é de até '.$qtdTotUsu.' usuários ativos, para adquirir mais usuários entre em contato com o nosso atendimento!');
                    }
                }
            }

            //Não pode permitir que o usuario logado se desative
            if($usuario_cod == Auth::user()->usuario_codigo && $request->statusUsuario == "D"){
                return redirect()->back()->with('error', 'Usuário logado não pode se desativar!');
            }

            //Limpa a mascara do cpf e verifica se foi informado corretamente
            if(!empty($request->cpf)){
                $replace = array("_", "/", "-", ".", " ");
                $cpfNew = str_replace($replace,"",$request->cpf);
            }else{
                $cpfNew = null;
            }

            if(!empty($cpfNew) && strlen($cpfNew) < 11){
                return redirect()->back()->with('error', 'Formatro do CPF informado é inválido!');
            }

            //Verifica se já existe o cpf informado registrado em outro usuario
            $cpfs = DB::table('users')->select('usuario_cpf')->where('usuario_codigo', '<>', $usuario_cod)->whereNotNull('usuario_rg')->get();

            foreach($cpfs as $cpf){

                if($cpfNew == $cpf->usuario_cpf){
                    return redirect()->back()->with('error', 'CPF informado já foi cadastrado em outro Usuário!');
                }
            }

            //Limpa a mascara do rg e verifica se foi informado corretamente
            if(!empty($request->rg)){
                $replace = array("_", "-", ".", " ");
                $rgNew = str_replace($replace,"",$request->rg);
            }else{
                $rgNew = null;
            }

            if(!empty($rgNew) && strlen($rgNew) < 9){
                return redirect()->back()->with('error', 'Formatro do RG informado é inválido!');
            }

            //Verifica se já existe o rg informado registrado em outro usuario
            $rgs = DB::table('users')->select('usuario_rg')->where('usuario_codigo', '<>', $usuario_cod)->whereNotNull('usuario_rg')->get();

            foreach($rgs as $rg){

                if($rgNew == $rg->usuario_rg){
                    return redirect()->back()->with('error', 'RG informado já foi cadastrado em outro Usuário!');
                }
            }

            //Ajusta a data de nascimento para o formato do banco
            if(!empty($request->dataNascimento)){
                $data_nas = substr($request->dataNascimento,-4).'-'.substr($request->dataNascimento,3,2).'-'.substr($request->dataNascimento,0,2);
            }else{
                $data_nas = null;
            }

            $atualizausuario = DB::table('users')
                ->where('id', $usuario)
                ->where('usuario_codigo', $usuario_cod)
                ->update(['name' => $request->nome,
                'usuario_status' => $request->statusUsuario,
                'usuario_cpf' => $cpfNew,
                'usuario_rg' => $rgNew,
                'usuario_sexo' => $request->sexo,
                'usuario_data_nascimento' => $data_nas,
                'usuario_empresa' => $request->empUsuario
            ]);
        
        }elseif($atualiza == 'contato'){

            //Limpa a mascara do celular e verifica se informou corretamente
            if(!empty($request->telCelular)){
                $replace = array("_", "(", ")", "-", " ");
                $telefoneCelular = str_replace($replace,"",$request->telCelular);

                if(strlen($telefoneCelular) < 11){
                    return redirect()->back()->with('error', 'Formatro do Telefone Celular informado é inválido!');
                }
            }else{
                $telefoneCelular = null;
            }

            //Limpa a mascara do telefone residencial e verifica se informou corretamente
            if(!empty($request->telResidencial)){
                $replace = array("_", "(", ")", "-", " ");
                $telefoneResidencial = str_replace($replace,"",$request->telResidencial);

                if(strlen($telefoneResidencial) < 10){
                    return redirect()->back()->with('error', 'Formatro do Telefone Residencial informado é inválido!');
                }
            }else{
                $telefoneResidencial = null;
            }

            //Verifica se ao menos um dos telefones foi informado
            if(empty($telefoneResidencial) && empty($telefoneCelular)){
                return redirect()->back()->with('error', 'Informe ao menos um dos Telefones');
            }

            //Verifica se existe o email cadastrado por que não pode repetir
            $qtdEmail = DB::table('users')->where('usuario_codigo', '<>', $usuario_cod)->where('email', $request->email)->count();

            if($qtdEmail > 0){
                return redirect()->back()->with('error', 'O Email informado já foi cadastrado em outro usuário!');
            }

            DB::table('users')
                ->where('id', $usuario)
                ->where('usuario_codigo', $usuario_cod)
                ->update(['email' => $request->email,
                'usuario_tipo_email' => $request->tipoEmail,
                'usuario_tel_residencial' => $telefoneResidencial,
                'usuario_tel_celular' => $telefoneCelular
            ]);
        
        }elseif($atualiza == 'permissao'){

            //Garantimos que apenas usuários Administradores e Master podem alterar permissões no sistema
            if(Auth::user()->usuario_tipo != 'ADM' && Auth::user()->usuario_tipo != 'M'){

                return redirect()->back()->with('error', 'Apenas usuários do tipo administrador podem alterar permissões no sistema!');

            }else{

                //Se o usuario for ADM ou M confirmamos se ele pode fazer essa alteração
                if(Auth::user()->usuario_altera_permissoes_acesso == 'N'){

                    return redirect()->back()->with('error', 'Seu usuário não pode alterar permissões no sistema!');
                }
            }

            DB::table('users')
                ->where('id', $usuario)
                ->where('usuario_codigo', $usuario_cod)
                ->update(['usuario_altera_permissoes_acesso' => $request->altPerAcesso,
                'usuario_acesso_pararametros' => $request->acessoParametros,
                'usuario_acesso_cadastros' => $request->acessoCadastros,
                'usuario_aut_desc' => $request->autorizaDesconto,
                'usuario_acesso_mod_servicos' => $request->acessoModSrv,
                'usuario_acesso_mod_nf' => $request->acessoModNf,
                'usuario_acesso_mod_nf_simp' => $request->acessoModNfSimp,
                'usuario_acesso_mod_cont_prod' => $request->acessoModConProd]);
        
        }elseif($atualiza == 'senha'){

            //Faz a máscara na senha para inserção no banco de dados
            $senha = Hash::make($request->novaSenha);

            $atualizausuario = DB::table('users')
                ->where('id', $usuario)
                ->where('usuario_codigo', $usuario_cod)
                ->update(['password' => $senha]);
        
        }                
        
        return redirect(route('usuario.editarCadastro', ['dadosUsuario' => $usuario_cod, 'tipo' => $tipo]))->with('success', 'Usuário atualizado com sucesso!');
    }
}
