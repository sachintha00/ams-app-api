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
        // Retrieve the email from the request header
        $fileName = $request->header('Email');

        if ($fileName) {
            // Build the file path and retrieve the database name
            $filePath = 'public/tenant/' . $fileName . '.txt';

            if (Storage::exists($filePath)) {
                $dbname = Storage::get($filePath);

                // Set the database connection dynamically
                Config::set('database.connections.pgsql.database', $dbname);

                // Reconnect to the tenant's database
                DB::purge('pgsql');
                DB::reconnect('pgsql');
            } else {
                abort(404, 'Tenant database configuration not found.');
            }
        } else {
            abort(400, 'Email header is missing.');
        }

        // Perform the default authentication check using the parent method
        return parent::handle($request, $next, ...$guards);
    }
}