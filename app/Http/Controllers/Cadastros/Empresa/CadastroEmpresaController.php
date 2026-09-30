<?php

namespace App\Http\Controllers\Cadastros\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cadastros\Empresa\CadastroEmpresa;

class CadastroEmpresaController extends Controller
{
    protected $empresa;
    
    public function __construct(CadastroEmpresa $empresa)
    {
        $this->empresa = $empresa;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home do Cadastro de Empresas
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $empresas = $this->empresa->all();

        return view('/cadastros/empresa/homeEmpresa', ['empresas' => $empresas]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/cadastros/empresa/cadastroEmpresa');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão da Nova Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Limpa a mascara do cnpj e verifica se foi informado corretamente
        if(!empty($request->cnpj)){
            $replace = array("_", "/", "-", ".", " ");
            $cnpjNew = str_replace($replace,"",$request->cnpj);
        }else{
            $cnpjNew = null;
        }

        if(strlen($cnpjNew) < 14){
            return redirect()->back()->with('error', 'Formatro do CNPJ informado é inválido!');
        }

        //Verifica se já existe o cnpj informado registrado em outra empresa
        $cnpjs = DB::table('cadastro_empresas')->selectRaw('empresa_cnpj')->get();

        foreach($cnpjs as $cnpj){

            if($cnpjNew == $cnpj->empresa_cnpj){
                return redirect()->back()->with('error', 'CNPJ informado já foi cadastrado!');
            }
        }

        //Adiciona o código da empresa
        $cnt=DB::select("SELECT count(*) as count FROM cadastro_empresas")[0]->count;

        if($cnt == 0){
            $codigo = 'E'.str_pad('1',5,'0',STR_PAD_LEFT);
        }else{
            $nextval=DB::select("SELECT last_value FROM cadastro_empresas_empresa_id_seq")[0]->last_value+1;
            $codigo = 'E'.str_pad($nextval,5,'0',STR_PAD_LEFT);
        }

        $dados = [
            'empresa_codigo' => $codigo,
            'empresa_nome' => $request->nome,
            'empresa_cnpj' => $cnpjNew,
            'empresa_insc_estadual' => $request->insEstadual,
            'empresa_insc_municipal' => $request->insMunicipal,
            'empresa_dt_inc' => date('Y-m-d'),
            'empresa_usu_alt' => Auth::user()->usuario_codigo       
        ];
        
        $novaEmpresa = CadastroEmpresa::create($dados);

        return redirect(route('cadastroEmpresa.edit', ['cadastroEmpresa' => $codigo]))->with('success', 'Empresa cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($cadastroEmpresa)
    {
        $resultadoEmpresa = $this->empresa->where('empresa_codigo', $cadastroEmpresa)->first();

        return view('/cadastros/empresa/editarCadastroEmpresa',['dadosEmpresa' => $resultadoEmpresa]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, CadastroEmpresa $cadastroEmpresa)
    {
        $atualiza = $request->query('atualiza');

        if($atualiza == 'contato'){//Aba de contato da empresa

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

            //Limpa a mascara do telefone comercial e verifica se informou corretamente
            if(!empty($request->telComercial)){
                $replace = array("_", "(", ")", "-", " ");
                $telefoneComercial = str_replace($replace,"",$request->telComercial);

                if(strlen($telefoneComercial) < 10){
                    return redirect()->back()->with('error', 'Formatro do Telefone Comercial informado é inválido!');
                }
            }else{
                $telefoneComercial = null;
            }

            if(empty($telefoneComercial) && empty($telefoneCelular)){
                return redirect()->back()->with('error', 'Informe ao menos um dos Telefones');
            }

            $cadastroEmpresa->update([
                'empresa_email' => $request->email,
                'empresa_tel_celular' => $telefoneCelular,
                'empresa_tel_comercial' => $telefoneComercial,
                'empresa_pref_contato' => $request->prefContato,
            ]);

        }elseif($atualiza == 'dados'){//Aba de dados gerais da empresa

            //Limpa a mascara do cpnj e verifica se informou corretamente
            if(!empty($request->cnpj)){
                $replace = array("_", "/", "-", ".", " ");
                $cnpjNew = str_replace($replace,"",$request->cnpj);
            }else{
                $cnpjNew = null;
            }

            if(strlen($cnpjNew) < 14){
                return redirect()->back()->with('error', 'Formatro do CNPJ informado é inválido!');
            }
    
            //Verifica se já existe o cnpj informado registrado em outra empresa
            $cnpjs = DB::table('cadastro_empresas')->selectRaw('empresa_cnpj')->where('empresa_codigo','<>',$cadastroEmpresa->empresa_codigo)->get();
    
            foreach($cnpjs as $cnpj){
    
                if($cnpjNew == $cnpj->empresa_cnpj){
                    return redirect()->back()->with('error', 'CNPJ informado já foi cadastrado!');
                }
            }

            if(!empty($request->dataFundacao)){
                $dataFundacao = Helper::limpaData($request->dataFundacao);
            }else{
                $dataFundacao = null;
            }

            if(empty($request->insEstadual)){
                $insEstadual = "ISENTO";
                $consumidorFin = 'S';
            }else{
                $insEstadual = $request->insEstadual;
                $consumidorFin = 'N';
            }

            $cadastroEmpresa->update([
                'empresa_nome' => $request->nome,
                'empresa_cnpj' => $cnpjNew,
                'empresa_insc_estadual' => $insEstadual,
                'empresa_insc_municipal' => $request->insMunicipal,
                'empresa_nome_logo' => $request->nomeLogo,
                'empresa_cnae' => $request->cnaeCod,
                'empresa_dt_fundacao' => $dataFundacao,
                'empresa_micro_emp' => $request->microEmp,
                'empresa_ramo_atividade' => $request->ramoAtiv,
                'empresa_org_publico' => $request->orgPub,
                'empresa_con_final' => $consumidorFin
            ]);

        }elseif($atualiza == 'smtp'){//Aba de dados do email smtp

            $cadastroEmpresa->update([
                'empresa_smtp_host' => $request->hostSMTP,
                'empresa_smtp_port' => $request->portaSMTP,
                'empresa_smtp_username' => $request->userSMTP,
                'empresa_smtp_password' => $request->senhaSMTP,
                'empresa_smtp_encryption' => $request->criptSMTP,
                'empresa_smtp_from_address' => $request->emailSMTP
            ]);
        }         
        
        $cadastroEmpresa->update([
            'empresa_dt_alt' => date('Y-m-d'),
            'empresa_usu_alt' => Auth::user()->usuario_codigo
        ]);
        
        return redirect(route('cadastroEmpresa.edit', ['cadastroEmpresa' => $cadastroEmpresa->empresa_codigo]))->with('success', 'Dados atualizados com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(CadastroEmpresa $cadastroEmpresa)
    {
        $cadastroEmpresa->delete();
        
        return redirect(route('cadastroEmpresa.index'))->with('success', 'Empresa excluída com sucesso!');
    }
}
