<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomAuthenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $fileName = $request->header('Email');
        $filePath = 'public/tenant/' . $fileName . '.txt';
        $dbname = Storage::get($filePath);

        // Set the database connection dynamically
        Config(['database.connections.pgsql.database' => $dbname]);

        // Reconnect to the tenant's database
        DB::reconnect('pgsql');
        
        // First, call the parent handle method to perform the default authentication check
        parent::handle($request, $next, ...$guards);

        return $next($request);
    }
}