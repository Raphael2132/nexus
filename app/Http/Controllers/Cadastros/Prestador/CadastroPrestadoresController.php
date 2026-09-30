<?php

namespace App\Http\Controllers\Cadastros\Prestador;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cadastros\Prestador\CadastroPrestadores;

class CadastroPrestadoresController extends Controller
{
    protected $prestador;
    
    public function __construct(CadastroPrestadores $prestador)
    {
        $this->prestador = $prestador;
    }
    
    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home do Cadastro de Prestador
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $totalPrestadores = $this->prestador->count();
        $prestadoresAtivos = $this->prestador->where('prestador_status','=','A')->count();
        $prestadoresDemitidos = $this->prestador->where('prestador_status','=','D')->count();

        $prestadores = $this->prestador->reorder('prestador_codigo', 'asc')->get();

        return view('/cadastros/prestador/homePrestadores',['prestadores'=>$prestadores,'totalPrestadores'=>$totalPrestadores,'prestadoresAtivos'=>$prestadoresAtivos,'prestadoresDemitidos'=>$prestadoresDemitidos]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Prestador
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/cadastros/prestador/formularioPrestador', ['acao' => 'N']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão do Novo Prestador
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        if(!empty($request->cpfPrestador)){

            $cpfPrestador = Helper::limpaCPF($request->cpfPrestador);

            if(strlen($cpfPrestador) < 11){
                return redirect()->back()->with('error', 'Formatro do CPF inválido!');
            }
        }else{
            return redirect()->back()->with('error', 'Por Favor informe o CPF do Prestador!');
        }

        //Verifica se já existe o cpf cnpj informado registrado em outro prestador
        $cpfs = DB::table('cadastro_prestadores')->where('prestador_cpf', $cpfPrestador)->count();

        if($cpfs > 0){
            return redirect()->back()->with('error', 'CPF informado já foi cadastrado!');
        }

        //Verifica se usa o intervalo de expediente
        if($request->usaInt == 'S'){

            $horaIniInt = Helper::limpaHoraMinuto($request->horaIniInt);
            $horaFinInt = Helper::limpaHoraMinuto($request->horaFinInt);

            //Verifica se o horario final é menor que o horario inicial
            if($horaIniInt > $horaFinInt){
                return redirect()->back()->with('error', 'Hora Ini. Intervalo não pode ser maior que a Hora Fin. Intervalo!');
            }
        }else{
            $horaIniInt = 0;
            $horaFinInt = 0;
        }

        //Verifica se usa o intervalo de expediente
        if($request->usaIntSab == 'S'){

            $horaIniIntSab = Helper::limpaHoraMinuto($request->horaIniIntSab);
            $horaFinIntSab = Helper::limpaHoraMinuto($request->horaFinIntSab);

            //Verifica se o horario final é menor que o horario inicial
            if($horaIniIntSab > $horaFinIntSab){
                return redirect()->back()->with('error', 'Hora Ini. Intervalo Sábado não pode ser maior que a Hora Fin. Intervalo Sábado!');
            }
        }else{
            $horaIniIntSab = 0;
            $horaFinIntSab = 0;
        }

        //Verifica se usa o intervalo de expediente
        if($request->usaIntDom == 'S'){

            $horaIniIntDom = Helper::limpaHoraMinuto($request->horaIniIntDom);
            $horaFinIntDom = Helper::limpaHoraMinuto($request->horaFinIntDom);

            //Verifica se o horario final é menor que o horario inicial
            if($horaIniIntDom > $horaFinIntDom){
                return redirect()->back()->with('error', 'Hora Ini. Intervalo Domingo não pode ser maior que a Hora Fin. Intervalo Domingo!');
            }
        }else{
            $horaIniIntDom = 0;
            $horaFinIntDom = 0;
        }

        $nextval=DB::select("SELECT nextval('sq_cad_prestadores')")[0]->nextval;
        $codigo = 'P'.str_pad($nextval,5,'0',STR_PAD_LEFT);

        $dados = [
            'prestador_codigo' => $codigo,
            'prestador_nome' => $request->nomePrestador,
            'prestador_cpf' => $cpfPrestador,
            'prestador_empresa' => $request->empresaPrestador,
            'prestador_set' => $request->setPrestador,
            'prestador_are' => $request->areaPrestador,
            'prestador_tur_cod' => $request->turPrestador,
            'prestador_int_srv' => $request->usaInt,
            'prestador_hr_ini_int' => $horaIniInt,
            'prestador_hr_fin_int' => $horaFinInt,
            'prestador_int_srv_sab' => $request->usaIntSab,
            'prestador_hr_ini_int_sab' => $horaIniIntSab,
            'prestador_hr_fin_int_sab' => $horaFinIntSab,
            'prestador_int_srv_dom' => $request->usaIntDom,
            'prestador_hr_ini_int_dom' => $horaIniIntDom,
            'prestador_hr_fin_int_dom' => $horaFinIntDom
        ];

        CadastroPrestadores::create($dados);
        
        return redirect(route('cadastroPrestador.edit', ['cadastroPrestador' => $codigo, 'tipo' => 'T']))->with('success', 'Prestador cadastrado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Exibe a Lista de Prestador
    |----------------------------------------------------------------------------------------------------
    */
    public function show($tipo)
    {
        if($tipo == 'A'){
            $prestadores = $this->prestador->where('prestador_status','A')->get();
        }elseif($tipo == 'D'){
            $prestadores = $this->prestador->where('prestador_status','D')->get();
        }else{
            $prestadores = $this->prestador->get();
        }

        return view('/cadastros/prestador/consultaPrestador',['prestadores' => $prestadores, 'tipo' => $tipo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção do Prestador
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($cadastroPrestador, Request $request)
    {
        $tipo = $request->tipo;

        $resultadoPrestador = $this->prestador->where('prestador_codigo', $cadastroPrestador)->first();

        return view('/cadastros/prestador/formularioPrestador',['dadosPrestador' => $resultadoPrestador, 'acao' => 'M', 'tipo' => $tipo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Prestador
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, CadastroPrestadores $cadastroPrestador)
    {
        $tipo = $request->query('tipo');
        $atualiza = $request->query('atualiza');

        if($atualiza == 'contato'){
            if(!empty($request->telResidencial)){
                
                $telefoneResidencial = Helper::limpaTelResidencial($request->telResidencial);

                if(strlen($telefoneResidencial) < 10){
                    return redirect()->back()->with('error', 'Formatro do Telefone Residencial informado é inválido!');
                }
            }else{
                $telefoneResidencial = null;
            }

            if(!empty($request->telCelular)){

                $telefoneCelular = Helper::limpaTelCelular($request->telCelular);

                if(strlen($telefoneCelular) < 11){
                    return redirect()->back()->with('error', 'Formatro do Telefone Celular informado é inválido!');
                }
            }else{
                $telefoneCelular = null;
            }

            if(empty($telefoneCelular) && empty($telefoneResidencial) ){
                return redirect()->back()->with('error', 'Informe ao menos um dos Telefones');
            }

            $cadastroPrestador->update([
                'prestador_email' => $request->emailPrestador,
                'prestador_tel_residencial' => $telefoneResidencial,
                'prestador_tel_celular' => $telefoneCelular,
                'prestador_tipo_email' => $request->tipoEmail
            ]);

        }elseif($atualiza == 'dados'){

            if(!empty($request->cpfPrestador)){
                $cpfPrestador = Helper::limpaCPF($request->cpfPrestador);

                if(strlen($cpfPrestador) < 11){
                    return redirect()->back()->with('error', 'Formatro do CPF inválido!');
                }
            }else{
                return redirect()->back()->with('error', 'Informe o CPF!');
            }

            //Verifica se já existe o cpf cnpj informado registrado em outro cliente
            $cpfs = DB::table('cadastro_prestadores')->where('prestador_codigo','<>',$cadastroPrestador->prestador_codigo)->where('prestador_cpf',$cpfPrestador)->count();

            if($cpfs > 0){
                return redirect()->back()->with('error', 'CPF informado já foi cadastrado!');
            }
    
            if(!empty($request->rgPrestador)){
                
                $rg = Helper::limpaRG($request->rgPrestador);

                if(strlen($rg) < 9){
                    return redirect()->back()->with('error', 'Formatro do RG inválido!');
                }
            }else{
                $rg = null;
            }

            if(!empty($request->dataNascimento)){
                $data_nas = Helper::limpaData($request->dataNascimento);
            }else{
                $data_nas = null;
            }

            if($request->statusPrestador == 'A' && !empty($request->dataDemissao)){
                return redirect()->back()->with('error', 'Com a Situação Ativo não deve ser informada a Data de Demissão!');
            }

            if($request->statusPrestador == 'D' && empty($request->dataDemissao)){
                return redirect()->back()->with('error', 'Com a Situação Demitido deve ser informada a Data de Demissão!');
            }

            if(!empty($request->dataAdmissao)){
                $data_adm = Helper::limpaData($request->dataAdmissao);
            }else{
                $data_adm = null;
            }

            if(!empty($request->dataDemissao)){
                $data_dem = Helper::limpaData($request->dataDemissao);
            }else{
                $data_dem = null;
            }

            if($request->prestadorUsuSis == 'S' && empty($request->codUsuPrestador) ){
                return redirect()->back()->with('error', 'Quando o Prestador tem acesso ao sistema é obrigatório informar o seu Código de Usuário!');
            }

            if($request->prestadorUsuSis == 'S'){
                //Verifica se já existe o codigo de usuario registrado em outro prestador
                $cnt_usu = DB::table('cadastro_prestadores')->where('prestador_codigo','<>',$cadastroPrestador->prestador_codigo)->where('prestador_usuario_cod',$request->codUsuPrestador)->count();

                if($cnt_usu > 0 ){
                    return redirect()->back()->with('error', 'O código de usuário selecionado já está cadastrado em outro prestador!');
                }

                $codPrest = $request->codUsuPrestador;
            }else{
                $codPrest = null;
            }

            $cadastroPrestador->update([
                'prestador_nome' => $request->nomePrestador,
                'prestador_empresa' => $request->empresaPrestador,
                'prestador_cpf' => $cpfPrestador,
                'prestador_rg' => $rg,
                'prestador_data_nascimento' => $data_nas,
                'prestador_sexo' => $request->sexoPrestador,
                'prestador_status' => $request->statusPrestador,
                'prestador_data_admissao' => $data_adm,
                'prestador_data_demissao' => $data_dem,
                'prestador_acesso_sis' => $request->prestadorUsuSis,
                'prestador_usuario_cod' => $codPrest
            ]);

        }elseif($atualiza == 'servico'){

            //Verifica se usa o intervalo de expediente
            if($request->usaInt == 'S'){

                $horaIniInt = Helper::limpaHoraMinuto($request->horaIniInt);
                $horaFinInt = Helper::limpaHoraMinuto($request->horaFinInt);

                //Verifica se o horario final é menor que o horario inicial
                if($horaIniInt > $horaFinInt){
                    return redirect()->back()->with('error', 'Hora Ini. Intervalo não pode ser maior que a Hora Fin. Intervalo!');
                }
            }else{
                $horaIniInt = 0;
                $horaFinInt = 0;
            }

            //Verifica se usa o intervalo de expediente
            if($request->usaIntSab == 'S'){

                $horaIniIntSab = Helper::limpaHoraMinuto($request->horaIniIntSab);
                $horaFinIntSab = Helper::limpaHoraMinuto($request->horaFinIntSab);

                //Verifica se o horario final é menor que o horario inicial
                if($horaIniIntSab > $horaFinIntSab){
                    return redirect()->back()->with('error', 'Hora Ini. Intervalo Sábado não pode ser maior que a Hora Fin. Intervalo Sábado!');
                }
            }else{
                $horaIniIntSab = 0;
                $horaFinIntSab = 0;
            }

            //Verifica se usa o intervalo de expediente
            if($request->usaIntDom == 'S'){

                $horaIniIntDom = Helper::limpaHoraMinuto($request->horaIniIntDom);
                $horaFinIntDom = Helper::limpaHoraMinuto($request->horaFinIntDom);

                //Verifica se o horario final é menor que o horario inicial
                if($horaIniIntDom > $horaFinIntDom){
                    return redirect()->back()->with('error', 'Hora Ini. Intervalo Domingo não pode ser maior que a Hora Fin. Intervalo Domingo!');
                }
            }else{
                $horaIniIntDom = 0;
                $horaFinIntDom = 0;
            }

            $cadastroPrestador->update([
                'prestador_set' => $request->setPrestador,
                'prestador_are' => $request->areaPrestador,
                'prestador_tur_cod' => $request->turPrestador,
                'prestador_int_srv' => $request->usaInt,
                'prestador_hr_ini_int' => $horaIniInt,
                'prestador_hr_fin_int' => $horaFinInt,
                'prestador_int_srv_sab' => $request->usaIntSab,
                'prestador_hr_ini_int_sab' => $horaIniIntSab,
                'prestador_hr_fin_int_sab' => $horaFinIntSab,
                'prestador_int_srv_dom' => $request->usaIntDom,
                'prestador_hr_ini_int_dom' => $horaIniIntDom,
                'prestador_hr_fin_int_dom' => $horaFinIntDom
            ]);
        }        
        
        return redirect(route('cadastroPrestador.edit', ['cadastroPrestador' => $cadastroPrestador->prestador_codigo, 'tipo' => $tipo]))->with('success', 'Prestador atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão do Prestador
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(CadastroPrestadores $cadastroPrestador)
    {
        $cadastroPrestador->delete();
        
        return redirect(route('cadastroPrestador.index'))->with('success', 'Prestador excluído com sucesso!');
    }
}
