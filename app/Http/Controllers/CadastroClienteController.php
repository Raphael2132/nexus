<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CadastroCliente;
use stdClass;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Helper;

class CadastroClienteController extends Controller
{
    protected $cliente;
    
    public function __construct(CadastroCliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function clientes($tipo)
    {
        if($tipo == 'J'){
            $clientes = $this->cliente->where('cliente_tipo_pessoa','=','J')->get();
        }elseif($tipo == 'F'){
            $clientes = $this->cliente->where('cliente_tipo_pessoa','=','F')->get();
        }elseif($tipo == 'M'){
            $data = date('Y-m-01');
            $clientes = $this->cliente->where('cliente_dt_inc','>=',$data)->get();
        }else{
            $clientes = $this->cliente->all();
        }

        return view('/cadastros/cliente/clientes',['clientes'=>$clientes,'tipo'=>$tipo]);
    }

    public function cadastro()
    {
        return view('/cadastros/cliente/cadastroClientes');
    }

    public function editar($dadosCliente, $tipo)
    {
        $resultadoCliente = $this->cliente->where('cliente_codigo','=',$dadosCliente)->get();

        return view('/cadastros/cliente/editarCadastroCliente',['dadosCliente'=>$resultadoCliente, 'tipo' => $tipo]);
    }

    public function create(){
        return view('home.clientes');
    }

    public function inserir(Request $request){

        /*if($request->tipoCadastro == 'F' && $request->tipoPessoa == 'F'){
            return redirect()->back()->with('error', 'Fornecedor precisa ser do tipo Jurídico!');
        }*/

        if(!empty($request->dataNascimento)){
            $data_nas = substr($request->dataNascimento,-4).'-'.substr($request->dataNascimento,3,2).'-'.substr($request->dataNascimento,0,2);
        }else{
            $data_nas = null;
        }

        if(!empty($request->cpfCnpj)){
            $replace = array("_", "/", "-", ".", " ");
            $cpfCnpj = str_replace($replace,"",$request->cpfCnpj);

            if($request->tipoPessoa == 'F' && strlen($cpfCnpj) < 11){
                return redirect()->back()->with('error', 'Formatro do CPF inválido!');
            }

            if($request->tipoPessoa == 'J' && strlen($cpfCnpj) < 14){
                return redirect()->back()->with('error', 'Formatro do CNPJ inválido!');
            }
        }else{
            return redirect()->back()->with('error', 'Por Favor informe o CPF / CNPJ do Cliente!');
        }

        //Verifica se já existe o cpf cnpj informado registrado em outro cliente
        $cpfsCnpjs = DB::table('cadastro_clientes')->select('cliente_cpf_cnpj')->get();

        foreach($cpfsCnpjs as $cpfCnpjExistente){

            if($cpfCnpj == $cpfCnpjExistente->cliente_cpf_cnpj){
                return redirect()->back()->with('error', 'CPF / CNPJ informado já foi cadastrado!');
            }
        }

        if(!empty($request->rg)){
            $replace = array("_", "-", ".", " ");
            $rg = str_replace($replace,"",$request->rg);

            if(strlen($rg) < 9){
                return redirect()->back()->with('error', 'Formatro do RG inválido!');
            }else{
                //Verifica se já existe o cpf cnpj informado registrado em outro cliente
                $rgs = DB::table('cadastro_clientes')->select('cliente_rg')->whereNotNull('cliente_rg')->get();

                foreach($rgs as $rgExistente){

                    if($rg == $rgExistente->cliente_rg){
                        return redirect()->back()->with('error', 'RG informado já foi cadastrado!');
                    }
                }
            }
        }else{
            $rg = null;
        }

        if($request->tipoCadastro == 'F'){

            if(empty($request->insEstadual)){
                $insEstadual = "ISENTO";
                $consumidorFin = 'S';
            }else{
                $insEstadual = $request->insEstadual;
                $consumidorFin = 'N';
            }

        }else{

            $insEstadual = $request->insEstadual;

            if($request->tipoPessoa == 'F'){
                $consumidorFin = 'S';  
            }else{
                $consumidorFin = 'N';
            }
        }

        if($request->tipoCadastro == 'C'){

            //$nextval=DB::select("SELECT last_value FROM clientes_cliente_id_seq")[0]->last_value+1;
            $nextval=DB::select("SELECT nextval('sq_cad_clientes')")[0]->nextval;

            $codigo = 'C'.str_pad($nextval,9,'0',STR_PAD_LEFT);
        }else{
            $nextval=DB::select("SELECT nextval('sq_cad_fornecedores')")[0]->nextval;

            $codigo = 'F'.str_pad($nextval,9,'0',STR_PAD_LEFT);
        }

        $dataInc = date('Y-m-d');

        $dados = [

            'cliente_codigo' => $codigo,
            'cliente_nome' => $request->nome,
            'cliente_tipo_pessoa' => $request->tipoPessoa,
            'cliente_cpf_cnpj' => $cpfCnpj,
            'cliente_sexo' => $request->sexo,
            'cliente_tipo_cadastro' => $request->tipoCadastro,
            'cliente_rg' => $rg,
            'cliente_insc_estadual' => $insEstadual,
            'cliente_insc_municipal' => $request->insMunicipal,    
            'cliente_data_nascimento' => $data_nas,  
            'cliente_dt_inc' => $dataInc,
            'cliente_usu_alt' => Auth::user()->usuario_codigo,
            'cliente_con_final' => $consumidorFin        
        ];
        
        $novoCliente = CadastroCliente::create($dados);

        $resultadoCliente = $this->cliente->where('cliente_codigo','=',$codigo)->get();
        
        return redirect(route('cliente.editarCadastro', ['dadosCliente' => $codigo, 'tipo' => 'C']))->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function destroy(CadastroCliente $cliente){
        
        $cliente->delete();
        
        return redirect(route('home.clientes'))->with('success', 'Cliente excluido com sucesso!');
    }

    public function update(Request $request, $cliente, $cliente_cod, $atualiza, $tipo){

        if($atualiza == 'contato'){
            if(!empty($request->telResidencial)){
                $replace = array("_", "(", ")", "-", " ");
                $telefoneResidencial = str_replace($replace,"",$request->telResidencial);

                if(strlen($telefoneResidencial) < 10){
                    return redirect()->back()->with('error', 'Formatro do Telefone Residencial informado é inválido!');
                }
            }else{
                $telefoneResidencial = null;
            }

            if(!empty($request->telCelular)){
                $replace = array("_", "(", ")", "-", " ");
                $telefoneCelular = str_replace($replace,"",$request->telCelular);

                if(strlen($telefoneCelular) < 11){
                    return redirect()->back()->with('error', 'Formatro do Telefone Celular informado é inválido!');
                }
            }else{
                $telefoneCelular = null;
            }

            if(!empty($request->telComercial)){
                $replace = array("_", "(", ")", "-", " ");
                $telefoneComercial = str_replace($replace,"",$request->telComercial);

                if(strlen($telefoneComercial) < 10){
                    return redirect()->back()->with('error', 'Formatro do Telefone Comercial informado é inválido!');
                }
            }else{
                $telefoneComercial = null;
            }

            if(empty($telefoneComercial) && empty($telefoneCelular) && empty($telefoneResidencial) ){
                return redirect()->back()->with('error', 'Informe ao menos um dos Telefones');
            }

            DB::table('cadastro_clientes')
            ->where('cliente_id', $cliente)
            ->where('cliente_codigo', $cliente_cod)
            ->update(['cliente_email' => $request->email,
                'cliente_tel_residencial' => $telefoneResidencial,
                'cliente_tel_celular' => $telefoneCelular,
                'cliente_tel_comercial' => $telefoneComercial,
                'cliente_tipo_email' => $request->tipoEmail,
                'cliente_pref_contato' => $request->prefContato,
            ]);
        }elseif($atualiza == 'dados'){

            if(!empty($request->cpfCnpj)){
                $replace = array("_","/", "-", ".", " ");
                $cpfCnpj = str_replace($replace,"",$request->cpfCnpj);
            }else{
                $cpfCnpj = null;
            }

            $tip_pess = $this->cliente->where('cliente_codigo','=',$cliente_cod)->get();

            if($tip_pess[0]['cliente_tipo_pessoa'] == 'F' && strlen($cpfCnpj) < 11){
                return redirect()->back()->with('error', 'Formatro do CPF inválido!');
            }

            if($tip_pess[0]['cliente_tipo_pessoa'] == 'J' && strlen($cpfCnpj) < 14){
                return redirect()->back()->with('error', 'Formatro do CNPJ inválido!');
            }

            //Verifica se já existe o cpf cnpj informado registrado em outro cliente
            $cpfsCnpjs = DB::table('cadastro_clientes')->select('cliente_cpf_cnpj')->where('cliente_codigo','<>',$cliente_cod)->get();

            foreach($cpfsCnpjs as $cpfCnpjExistente){

                if($cpfCnpj == $cpfCnpjExistente->cliente_cpf_cnpj){
                    return redirect()->back()->with('error', 'CPF / CNPJ informado já foi cadastrado!');
                }
            }
    
            if(!empty($request->rg)){
                $replace = array("_","-", ".", " ");
                $rg = str_replace($replace,"",$request->rg);

                if($tip_pess[0]['cliente_tipo_pessoa'] == 'F' && strlen($rg) < 9){
                    return redirect()->back()->with('error', 'Formatro do RG inválido!');
                }
            }else{
                $rg = null;
            }

            if(!empty($request->dataNascimento)){
                $data_nas = substr($request->dataNascimento,-4).'-'.substr($request->dataNascimento,3,2).'-'.substr($request->dataNascimento,0,2);
            }else{
                $data_nas = null;
            }

            if(!empty($request->dataFundacao)){
                $dataFundacao = Helper::limpaData($request->dataFundacao);
            }else{
                $dataFundacao = null;
            }

            DB::table('cadastro_clientes')
            ->where('cliente_id', $cliente)
            ->where('cliente_codigo', $cliente_cod)
            ->update(['cliente_nome' => $request->nome,
                'cliente_cpf_cnpj' => $cpfCnpj,
                'cliente_rg' => $rg,
                'cliente_data_nascimento' => $data_nas,
                'cliente_sexo' => $request->sexo,
                'cliente_insc_estadual' => $request->insEstadual,
                'cliente_insc_municipal' => $request->insMunicipal,
                'cliente_cnae' => $request->cnaeCod,
                'cliente_dt_fundacao' => $dataFundacao,
                'cliente_micro_emp' => $request->microEmp,
                'cliente_ramo_atividade' => $request->ramoAtiv,
                'cliente_org_publico' => $request->orgPub
            ]);
        }   
        
        DB::table('cadastro_clientes')
        ->where('cliente_id', $cliente)
        ->where('cliente_codigo', $cliente_cod)
        ->update(['cliente_dt_alt' => date('Y-m-d'),
            'cliente_usu_alt' => Auth::user()->usuario_codigo
        ]);
        
        return redirect(route('cliente.editarCadastro', ['dadosCliente' => $cliente_cod, 'tipo' => $tipo]))->with('success', 'Cliente atualizado com sucesso!');
    }

    //Busca o codigo do grupo do cnae por AJAX
    public function carregaCnaeGrp($codigo)
    {  
        $cnaeGrp = DB::table('cnae_grupos')->where('cnaegrp_div', $codigo)->orderby('cnaegrp_grp', 'asc')->get();
       
        foreach($cnaeGrp as $grupo) {
            
            $grupos_ajax[] = array(
                'id'	=> $grupo->cnaegrp_grp,
                'cod_grupo' => $grupo->cnaegrp_grp.' - '.$grupo->cnaegrp_desc,
            );
        }  

        return response()->json(['success' => true, 'grupos_ajax' => $grupos_ajax]);
    }

    //Busca os codigos do cnae por AJAX
    public function carregaCnaeCod($divisao,$grupo)
    {  
        $cnaeCod = DB::table('cnae_codigos')->where('cnaesub_div', $divisao)->where('cnaesub_grp', $grupo)->orderby('cnaesub_cod', 'asc')->get();
       
        foreach($cnaeCod as $codigo) {
            
            $codigos_ajax[] = array(
                'id'	=> $codigo->cnaesub_cod,
                'cod_cnae' => $codigo->cnaesub_cod.' - '.$codigo->cnaesub_desc,
            );
        }  

        return response()->json(['success' => true, 'codigos_ajax' => $codigos_ajax]);
    }
}
