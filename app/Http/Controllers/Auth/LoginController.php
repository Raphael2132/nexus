<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Importar o Carbon para trabalhar com datas

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Sobrescrever o método para incluir a verificação de status do usuário e validade da licença da empresa.
     */
    protected function attemptLogin(Request $request)
    {
        // Tentar autenticar o usuário com as credenciais fornecidas
        $credentials = $request->only('email', 'password');
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        // Verificar se o usuário está desativado
        if ($user && $user->usuario_status === 'D') {
            return false; // Bloquear o login se o usuário estiver desativado
        }

        // Verificar se a licença da empresa associada ao usuário está válida
        if ($user) {
            // Buscar a data de validade da empresa associada ao usuário
            $empresaValidade = DB::table('parametros_sis_modulos')
                ->where('modulo_empresa_codigo', $user->usuario_empresa)
                ->value('modulo_dt_validade');

            // Verificar se a validade da licença expirou
            if ($empresaValidade && Carbon::parse($empresaValidade)->lt(Carbon::today())) {
                return false; // Bloquear o login se a licença estiver expirada
            }
        }

        // Caso contrário, continuar o processo de login normalmente
        return Auth::attempt($credentials, $request->filled('remember'));
    }

    /**
     * Personalizar a resposta de erro de login.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        // Verificar se o usuário está desativado
        if ($user && $user->usuario_status === 'D') {
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'O usuário foi desativado.']);
        }

        // Verificar se a licença da empresa está expirada
        if ($user) {
            $empresaValidade = DB::table('parametros_sis_modulos')
                ->where('modulo_empresa_codigo', $user->usuario_empresa)
                ->value('modulo_dt_validade');

            if ($empresaValidade && Carbon::parse($empresaValidade)->lt(Carbon::today())) {
                // Formatando a data de expiração no formato d/m/Y
                $dataExpiracao = Carbon::parse($empresaValidade)->format('d/m/Y');
                
                return redirect()->back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => "A licença da empresa está expirada. Licença expirada dia $dataExpiracao."]);
            }
        }

        // Retornar erro padrão de login
        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => trans('auth.failed')]);
    }
}
