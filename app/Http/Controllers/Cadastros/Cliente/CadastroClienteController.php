<?php

namespace App\Http\Controllers\Cadastros\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Helper;
use App\Models\Cadastros\Cliente\CadastroCliente;

class CadastroClienteController extends Controller
{
    protected $cliente;
    
    public function __construct(CadastroCliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home do Cadastro de Clientes
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        //Monta variaveis dos cards
        $cliJuridico = $this->cliente->where('cliente_tipo_pessoa','J')->count();
        $cliFisico = $this->cliente->where('cliente_tipo_pessoa','F')->count();
        $cliTot = $this->cliente->count();
        $clientesTot = $this->cliente->where('cliente_tipo_cadastro','C')->count();
        $clientesFis = $this->cliente->where('cliente_tipo_cadastro','C')->where('cliente_tipo_pessoa','F')->count();
        $clientesJur = $this->cliente->where('cliente_tipo_cadastro','C')->where('cliente_tipo_pessoa','J')->count();
        $clientesFor = $this->cliente->where('cliente_tipo_cadastro','F')->count();
        
        //Seta a data para português
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
        //date_default_timezone_set('America/Sao_Paulo');

        /* ***** Gráfico de Clientes e Fornecedores ***** */
        $meses = "[";
        $grafJ = "[";
        $grafF = "[";
        $grafFornecedores = "[";

        // Monta as variáveis para o JS dos últimos seis meses do gráfico
        for ($i = 5; $i >= 0; $i--) { // Ajustado para pegar 6 meses incluindo o mês atual
            $dt_ini = date('Y-m-01', strtotime("-$i month"));
            $dt_fin = date("Y-m-t", strtotime("-$i month"));
            $mes_nom = ucfirst(strftime("%B", strtotime($dt_ini)));

            // Adiciona o nome do mês no array
            $meses .= "'".$mes_nom."',";

            // Conta os clientes do tipo "J" para o mês
            $cntJ = $this->cliente->where('cliente_tipo_pessoa', 'J')
                                ->where('cliente_tipo_cadastro', 'C')
                                ->whereBetween('cliente_dt_inc', [$dt_ini, $dt_fin])
                                ->count();
            $grafJ .= $cntJ.",";

            // Conta os clientes do tipo "F" para o mês
            $cntF = $this->cliente->where('cliente_tipo_pessoa', 'F')
                                ->where('cliente_tipo_cadastro', 'C')
                                ->whereBetween('cliente_dt_inc', [$dt_ini, $dt_fin])
                                ->count();
            $grafF .= $cntF.",";

            // Conta os fornecedores para o mês
            $cntFor = $this->cliente->where('cliente_tipo_cadastro', 'F')
                                ->whereBetween('cliente_dt_inc', [$dt_ini, $dt_fin])
                                ->count();
            $grafFornecedores .= $cntFor.",";
        }

        // Fecha os arrays de meses e gráficos
        $meses = rtrim($meses, ',') . "]"; // Remove a última vírgula e fecha o array
        $grafJ = rtrim($grafJ, ',') . "]"; // Remove a última vírgula e fecha o array
        $grafF = rtrim($grafF, ',') . "]"; // Remove a última vírgula e fecha o array
        $grafFornecedores = rtrim($grafFornecedores, ',') . "]"; // Remove a última vírgula e fecha o array

        //Monta a data do mês atual e dos ultimos 5 mês 
        // Data atual
        $dataIni = new \DateTime();
        // Subtrair 6 meses
        $dataIni->modify('-5 months');
        // Definir o dia como 01
        $dataIni->modify('first day of this month');
        $dataFinal = date('Y-m-d');
        // Formatar as datas no formato correto (Y-m-d)
        $dataIni = $dataIni->format('Y-m-d');

        $clientes = $this->cliente->where('cliente_tipo_cadastro', 'C')->whereBetween('cliente_dt_inc', [$dataIni, $dataFinal])->get();
        $fornecedores = $this->cliente->where('cliente_tipo_cadastro', 'F')->whereBetween('cliente_dt_inc', [$dataIni, $dataFinal])->get();
      
        return view('/cadastros/cliente/homeClientes',[
            'cliJuridico' => $cliJuridico,
            'cliFisico' => $cliFisico,
            'cliTot' => $cliTot,
            'meses' => $meses,
            'grafJ' => $grafJ,
            'grafF' => $grafF,
            'clientes' => $clientes,
            'clientesTot' => $clientesTot,
            'clientesFis' => $clientesFis,
            'clientesJur' => $clientesJur,
            'clientesFor' => $clientesFor,
            'fornecedores' => $fornecedores,
            'grafFornecedores' => $grafFornecedores,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/cadastros/cliente/cadastroClientes');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão do Novo Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
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
        
        return redirect(route('cadastroCliente.edit', ['cadastroCliente' => $codigo, 'tipo' => 'C']))->with('success', 'Cliente cadastrado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Exibe a Lista de Clientes
    |----------------------------------------------------------------------------------------------------
    */
    public function show($tipo)
    {
        if($tipo == 'J'){

            $clientes = $this->cliente->where('cliente_tipo_pessoa','J')->get();

        }elseif($tipo == 'F'){

            $clientes = $this->cliente->where('cliente_tipo_pessoa','F')->get();

        }elseif($tipo == 'M'){

            $data = date('Y-m-01');
            $clientes = $this->cliente->where('cliente_dt_inc','>=',$data)->get();

        }elseif($tipo == 'CLI'){

            $clientes = $this->cliente->where('cliente_tipo_cadastro','C')->get();

        }elseif($tipo == 'CLF'){

            $clientes = $this->cliente->where('cliente_tipo_cadastro','C')->where('cliente_tipo_pessoa','F')->get();

        }elseif($tipo == 'CLJ'){

            $clientes = $this->cliente->where('cliente_tipo_cadastro','C')->where('cliente_tipo_pessoa','J')->get();

        }elseif($tipo == 'FOR'){

            $clientes = $this->cliente->where('cliente_tipo_cadastro','F')->get();
        }else{

            $clientes = $this->cliente->all();
        }

        return view('/cadastros/cliente/clientes',['clientes'=>$clientes,'tipo'=>$tipo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção do Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($cadastroCliente, Request $request)
    {
        $tipo = $request->tipo;

        $resultadoCliente = $this->cliente->where('cliente_codigo', $cadastroCliente)->first();

        return view('/cadastros/cliente/editarCadastroCliente',['dadosCliente' => $resultadoCliente, 'tipo' => $tipo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, CadastroCliente $cadastroCliente)
    {
        $tipo = $request->query('tipo');
        $atualiza = $request->query('atualiza');
        
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

            $cadastroCliente->update([
                'cliente_email' => $request->email,
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

            //$tip_pess = $this->cliente->where('cliente_codigo',$cliente_cod)->get();

            if($request->tipoCadastro == 'F' && strlen($cpfCnpj) < 11){
                return redirect()->back()->with('error', 'Formatro do CPF inválido!');
            }

            if($request->tipoCadastro == 'J' && strlen($cpfCnpj) < 14){
                return redirect()->back()->with('error', 'Formatro do CNPJ inválido!');
            }
            
            //Verifica se já existe o cpf cnpj informado registrado em outro cliente
            $cpfsCnpjs = DB::table('cadastro_clientes')->select('cliente_cpf_cnpj')->where('cliente_codigo','<>',$request->codCliente)->get();

            foreach($cpfsCnpjs as $cpfCnpjExistente){

                if($cpfCnpj == $cpfCnpjExistente->cliente_cpf_cnpj){
                    return redirect()->back()->with('error', 'CPF / CNPJ informado já foi cadastrado!');
                }
            }
    
            if(!empty($request->rg)){
                $replace = array("_","-", ".", " ");
                $rg = str_replace($replace,"",$request->rg);

                if($request->tipoCadastro == 'F' && strlen($rg) < 9){
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

            $cadastroCliente->update([
                'cliente_nome' => $request->nome,
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
        
        $cadastroCliente->update([
            'cliente_dt_alt' => date('Y-m-d'),
            'cliente_usu_alt' => Auth::user()->usuario_codigo
        ]);
        
        return redirect(route('cadastroCliente.edit', ['cadastroCliente' => $cadastroCliente->cliente_codigo, 'tipo' => $tipo]))->with('success', 'Cliente atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão do Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(CadastroCliente $cadastroCliente)
    {
        $cadastroCliente->delete();
        
        return redirect(route('cadastroCliente.index'))->with('success', 'Cliente excluido com sucesso!');
    }
}
