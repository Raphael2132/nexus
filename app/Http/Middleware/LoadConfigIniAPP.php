<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LoadConfigIniAPP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Aqui temos que acertar quando tivermos o domínio correto do sistema para separar o subdominio do cliente do endereço de acesso
        $host = $request->getHost();
        $domain = str_replace(['http://', 'https://', 'www.'], '', $host);
        //$domain = explode('.', $domain)[0];

        // Carregar o caminho do arquivo config.ini
        $iniPath = config('app.empresa_ini_path');
        
        //Verificamos se o arquivo de inicialização existe
        if (file_exists($iniPath)) {
            $ini = parse_ini_file($iniPath, true);

            if (isset($ini[$domain])) {
                $cnpj = $ini[$domain]['cnpj'];
                $codigo = $ini[$domain]['codigo'];
                $database = $ini[$domain]['database'];
                $username = $ini[$domain]['username'];
                $password = $ini[$domain]['password'];

                // Configurar a conexão ao banco de dados dinamicamente pelo banco do cliente do acesso
                Config::set('database.connections.empresa', [
                    'driver' => 'pgsql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '5432'),
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'charset' => 'utf8',
                    'prefix' => '',
                    'schema' => 'public',
                    'sslmode' => 'prefer',
                ]);

                //Define a conexão a ser utilizada
                DB::setDefaultConnection('empresa');

                //Verifica se o Host é o localhost ou produção
                //Se for localhost busca a imagem da tela de login da empresa logada de local diferente
                if($domain == '127.0.0.1'){

                    $logoPath = public_path($cnpj . '/file/img/' . $codigo . '_logo.png');
                        
                    if (file_exists($logoPath)) {
                        config([
                            'adminlte.auth_logo.img.path' => $cnpj . '/file/img/' . $codigo . '_logo.png',
                        ]);
                    }

                }else{
                    
                    $logoPath = '/home/'. $cnpj . '/file/img/' . $codigo . '_logo.png';

                    if (file_exists($logoPath)) {

                        $logoUrl = route('logo.file', ['cnpj' => $cnpj, 'filename' => $codigo . '_logo.png']);

                        config([
                            'adminlte.auth_logo.img.path' => $logoUrl,
                        ]);
                    }
                }

            } else {
                Log::warning("Configuração para o domínio {$domain} não encontrada no arquivo ini.");
            }
        } else {
            Log::error("Arquivo ini não encontrado: {$iniPath}");
        }

        return $next($request);
    }
}
