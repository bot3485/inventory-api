<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class UpdateUserNetworkData
{
    /**
     * Handle an incoming request.
     * * This middleware automatically captures and updates network-related
     * metadata for authenticated users on every request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Check if the user is authenticated
        if ($request->user()) {

            // 2. Update or create a record in the user_details table
            $request->user()->details()->updateOrCreate(
                ['user_id' => $request->user()->id], // Search criteria (unique identifier)
                [
                    'ip_address'     => $request->ip(),           // Capture the request IP
                    'user_agent'     => $request->userAgent(),    // Capture the raw browser/client string
                    'last_active_at' => now(),
                    // Note: Third-party libraries (e.g., Jenssegers/Agent) can be used
                    // for detailed OS/Browser parsing, but raw data is stored for now.
                ]
            );
        }

        return $next($request);
    }
}
