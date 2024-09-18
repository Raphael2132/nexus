<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use stdClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Facades\Modulos;

class CadastroUsuarioController extends Controller
{
    protected $usuario;
    
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    //Chama a app de consulta com o pré filtro
    public function usuarios($tipo)
    {
        //Se não for usuário MASTER o logado no sistema não exibe ele
        if(Auth::user()->usuario_codigo != 'MASTER'){

            if($tipo == 'T'){
                $usuarios = $this->usuario->where('usuario_codigo','<>','MASTER')->get();
            }elseif($tipo == 'A'){
                $usuarios = $this->usuario->where('usuario_status','=','A')->where('usuario_codigo','<>','MASTER')->get();
            }elseif($tipo == 'D'){
                $usuarios = $this->usuario->where('usuario_status','=','D')->where('usuario_codigo','<>','MASTER')->get();
            }elseif($tipo == 'ADM'){
                $usuarios = $this->usuario->where('usuario_tipo','=','A')->where('usuario_codigo','<>','MASTER')->get();
            }else{
                $usuarios = $this->usuario->where('usuario_tipo','=','P')->where('usuario_codigo','<>','MASTER')->get();
            }

        }else{

            if($tipo == 'T'){
                $usuarios = $this->usuario->all();
            }elseif($tipo == 'A'){
                $usuarios = $this->usuario->where('usuario_status','=','A')->get();
            }elseif($tipo == 'D'){
                $usuarios = $this->usuario->where('usuario_status','=','D')->get();
            }elseif($tipo == 'ADM'){
                $usuarios = $this->usuario->where('usuario_tipo','=','A')->get();
            }else{
                $usuarios = $this->usuario->where('usuario_tipo','=','P')->get();
            }
        }

        return view('/cadastros/usuario/usuarios',['usuarios'=>$usuarios,'tipo'=>$tipo]);
    }

    //Chama a app de cadastro de novo usuario
    public function cadastro($tipo)
    {
        return view('/cadastros/usuario/cadastroUsuarios',['tipo' => $tipo]);
    }

    //Chama a app home do cadastro de usuarios
    public function create(){
        return view('home.usuarios');
    }

    //Chama a app de edição dos dadosdo usuario
    public function editar($dadosUsuario, $tipo)
    {
        //Apenas o Usuario Master pode editar o usuario master
        if(Auth::user()->usuario_codigo != 'MASTER' && $dadosUsuario == 'MASTER'){
            return redirect()->back()->with('error', 'Apenas o usuário MASTER tem permição de acessar os dados do Usuário Master do sistema!');
        }

        $resultadoUsuario = $this->usuario->where('usuario_codigo','=',$dadosUsuario)->get();

        return view('/cadastros/usuario/editarCadastroUsuario',['dadosUsuario'=>$resultadoUsuario, 'tipo' => $tipo]);
    }

    //Insere os dados de um novo usuario
    public function inserir(Request $request, $tipo){

        $modulos = Modulos::getModulos();

        $qtdUser = DB::table('users')->where('usuario_codigo', '<>', 'MASTER')->where('usuario_status', 'A')->where('usuario_empresa', $request->empresa)->count();

        //Não permitir cadastrar usuario se ja tiver cadastrado a quantidade limite da empresa
        if($qtdUser >= $modulos->modulo_qtd_usuarios){
            return redirect()->back()->with('info', 'A quantidade máxima de Usuários ativos permitida para a empresa '.$request->empresa.' já foi atingida! A quantidade permitida é de até '.$qtdUser.' usuários, para adquirir mais usuários entre em contato com o nosso atendimento!');
        }

        //Verifica se existe o email cadastrado por que não pode repetir
        $qtdEmail = DB::table('users')->where('email', $request->email)->count();

        if($qtdEmail > 0){
            return redirect()->back()->with('error', 'O Email informado já foi cadastrado em outro usuário!');
        }

        //$nextval=DB::select("SELECT last_value FROM users_id_seq")[0]->last_value+1;
        $nextval=DB::select("SELECT nextval('sq_cad_usuarios')")[0]->nextval;

        $codigo = 'U'.str_pad($nextval,5,'0',STR_PAD_LEFT);

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
        
        $novoUsuario = User::create($dados);
        
        return redirect(route('usuario.editarCadastro', ['dadosUsuario' => $codigo, 'tipo' => $tipo]))->with('success', 'Usuário cadastrado com sucesso!');
    }

    //Exclui os dados de um usuário
    public function destroy(User $usuario){

        if($usuario->usuario_codigo == 'MASTER'){
            return redirect()->back()->with('error', 'Usuário MASTER não pode ser excluído!');
        }

        $usuario->delete();
        
        return redirect(route('home.usuarios'))->with('success', 'Usuário excluído com sucesso!');
    }

    //Realiza a atualização dos dadosdo usuario
    public function update(Request $request, $usuario, $usuario_cod, $atualiza, $tipo){

        //Verifica qual parte das informações estão sendo atualizados
        if($atualiza == 'dados'){

            //Não podemos permitir que um usuario desativado seja ativado novamente se o limite já foi atingido
            if($request->statusUsuario == 'A'){

                $stsUsu = DB::table('users')->where('usuario_codigo', $usuario_cod)->where('usuario_empresa', $request->empUsuario)->first();

                if($stsUsu->usuario_status == "D"){

                    $modulos = Modulos::getModulos();

                    $qtdUser = DB::table('users')->where('usuario_codigo', '<>', 'MASTER')->where('usuario_status', 'A')->where('usuario_empresa', $request->empUsuario)->count();
                    
                    //Não permitir cadastrar usuario se ja tiver cadastrado a quantidade limite da empresa
                    if($qtdUser >= $modulos->modulo_qtd_usuarios){
                        return redirect()->back()->with('info', 'A quantidade máxima de Usuários ativos permitida para a empresa '.$request->empUsuario.' já foi atingida! A quantidade permitida é de até '.$qtdUser.' usuários, para adquirir mais usuários entre em contato com o nosso atendimento!');
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

            if($request->altPerAcesso == 'S'){
                //Verifica o tipo do usuario
                $dadosUsu = DB::table('users')->where('usuario_codigo', $usuario_cod)->get();

                if($dadosUsu[0]->usuario_tipo != 'A'){
                    return redirect()->back()->with('error', 'Apenas usuários do tipo administrador podem alterar permissões no sistema!');
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
                'usuario_acesso_mod_nf' => $request->acessoModNf]);
        
        }elseif($atualiza == 'senha'){

            $senha = Hash::make($request->novaSenha);

            $atualizausuario = DB::table('users')
                ->where('id', $usuario)
                ->where('usuario_codigo', $usuario_cod)
                ->update(['password' => $senha]);
        
        }                
        
        return redirect(route('usuario.editarCadastro', ['dadosUsuario' => $usuario_cod, 'tipo' => $tipo]))->with('success', 'Usuário atualizado com sucesso!');
    }
}
