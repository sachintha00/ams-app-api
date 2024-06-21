<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class DBchange
{
    public function handle(Request $request, Closure $next): Response
    {
        $userDatabase = Auth::user()->tenant_db_name;
        $connectionName = "pgsql";

        config([
            "database.connections.$connectionName" => [
                'driver' => 'pgsql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '5432'),
                'database' => $userDatabase,
                'username' => env('DB_USERNAME', 'forge'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8',
                'prefix' => '',
                'schema' => 'public',
            ],
        ]);

        DB::purge($connectionName);
        DB::reconnect($connectionName);


        return $next($request);
    }
}
