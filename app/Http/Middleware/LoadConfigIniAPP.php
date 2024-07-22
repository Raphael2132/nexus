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
        $host = $request->getHost();
        $domain = str_replace(['http://', 'https://', 'www.'], '', $host);
        echo $domain;
        //$domain = explode('.', $domain)[0];

        // Carregar o caminho do arquivo .ini da configuração
        $iniPath = config('app.empresa_ini_path');

        echo ' / '.$iniPath;exit;
        
        if (file_exists($iniPath)) {
            echo 'achei o arquivo';exit;
            $ini = parse_ini_file($iniPath, true);

            if (isset($ini[$domain])) {
                $cnpj = $ini[$domain]['cnpj'];
                $database = $ini[$domain]['database'];
                $username = $ini[$domain]['username'];
                $password = $ini[$domain]['password'];

                // Configurar a conexão ao banco de dados dinamicamente
                Config::set('database.connections.empresa', [
                    'driver' => 'pgsql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '5432'),
                    'database' => $database,
                    'username' => env('DB_USERNAME', $username),
                    'password' => env('DB_PASSWORD', $password),
                    'charset' => 'utf8',
                    'prefix' => '',
                    'schema' => 'public',
                    'sslmode' => 'prefer',
                ]);

                DB::setDefaultConnection('empresa');

                // Carregar dados da empresa para configurar o AdminLTE
                $empresa = DB::table('cadastro_empresas')->where('empresa_cnpj', $cnpj)->first();

                if ($empresa) {
                    $logoPath = public_path($empresa->empresa_cnpj . '/file/img/' . $empresa->empresa_codigo . '_logo.png');
                    
                    if (file_exists($logoPath)) {
                        config([
                            'adminlte.auth_logo.img.path' => $empresa->empresa_cnpj . '/file/img/' . $empresa->empresa_codigo . '_logo.png',
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
