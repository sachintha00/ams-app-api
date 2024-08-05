<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

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
        $tenanttoken = $request->header('TenantToken');

        // if ($tenanttoken) {
        //     $randomStringLength = 27;  // Length of the random string
        //     $decryptedTenant = Crypt::decrypt($tenanttoken);  // Decrypt the value
            
        //     // Remove the random string by slicing the string from the position after the random string
        //     $originalTenant = substr($decryptedTenant, $randomStringLength);

        //     // Set the database connection dynamically
        //     Config::set('database.connections.pgsql.database', $originalTenant);

        //     // Reconnect to the tenant's database
        //     DB::purge('pgsql');
        //     DB::reconnect('pgsql');

        // } else {
        //     abort(400, 'TenantToken is missing.');
        // }


        // if ($tenanttoken) {
        //     try {
        //         $randomStringLength = 27;  // Ensure this matches the token generation logic
        //         $decryptedTenant = Crypt::decrypt($tenanttoken);  // Decrypt the value

        //         // Ensure the decrypted token length is greater than the random string length
        //         if (strlen($decryptedTenant) > $randomStringLength) {
        //             // Remove the random string by slicing the string from the position after the random string
        //             $originalTenant = substr($decryptedTenant, $randomStringLength);

        //             // Set the database connection dynamically
        //             Config::set('database.connections.pgsql.database', $originalTenant);

        //             // Reconnect to the tenant's database
        //             DB::purge('pgsql');
        //             DB::reconnect('pgsql');
        //         } else {
        //             abort(400, 'Invalid TenantToken format.');
        //         }
        //     } catch (DecryptException $e) {
        //         // Handle decryption failure
        //         abort(400, 'Invalid TenantToken.');
        //     }
        // } else {
        //     abort(400, 'TenantToken is missing.');
        // }

        if (!$tenanttoken) {
            abort(400, 'TenantToken is missing.');
        }

        // Implement rate limiting to prevent brute-force attacks
        $rateLimiterKey = 'TenantToken|' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            abort(429, 'Too many attempts. Please try again later.');
        }

        try {
            // Validate the structure of the token before decryption
            if (!$this->isValidTokenFormat($tenanttoken)) {
                Log::warning('Invalid TenantToken format detected.', ['token' => $tenanttoken, 'ip' => $request->ip()]);
                abort(400, 'Invalid TenantToken format.');
            }

            $randomStringLength = 27;  // Ensure this matches the token generation logic
            $decryptedTenant = Crypt::decrypt($tenanttoken);  // Decrypt the value

            if (strlen($decryptedTenant) <= $randomStringLength) {
                abort(400, 'Decrypted TenantToken is invalid.');
            }

            // Remove the random string by slicing the string from the position after the random string
            $originalTenant = substr($decryptedTenant, $randomStringLength);

            // Set the database connection dynamically with secure methods
            Config::set('database.connections.pgsql.database', $this->secureDatabaseName($originalTenant));

            // Reconnect to the tenant's database
            DB::purge('pgsql');
            DB::reconnect('pgsql');

        } catch (DecryptException $e) {
            // Log decryption failure for auditing purposes
            Log::error('Failed to decrypt TenantToken.', ['error' => $e->getMessage(), 'ip' => $request->ip()]);
            abort(400, 'Invalid TenantToken.');
        }

        // Reset the rate limiter on successful attempt
        RateLimiter::clear($rateLimiterKey);

        // Perform the default authentication check using the parent method
        return parent::handle($request, $next, ...$guards);
    }

        /**
     * Validate the structure of the TenantToken.
     *
     * @param  string  $token
     * @return bool
     */
    private function isValidTokenFormat($token)
    {
        // Example: Check if the token is a valid base64-encoded string
        return preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $token);
    }

    /**
     * Secure the database name for connection.
     *
     * @param  string  $tenant
     * @return string
     */
    private function secureDatabaseName($tenant)
    {
        // Additional sanitization and validation logic for the database name
        return preg_replace('/[^a-zA-Z0-9_]/', '', $tenant);  // Example: Only allow alphanumeric and underscores
    }
}