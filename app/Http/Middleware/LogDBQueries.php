<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Log;

class LogDBQueries
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        if ( !env( 'DB_LOG', false ) ) {
            return $next( $request );
        }

        DB::enableQueryLog();

        $response = $next( $request );

        foreach ( DB::getQueryLog() as $log ) {
            Log::debug( $log[ 'query' ], [ 'bindings' => $log[ 'bindings' ], 'time' => $log[ 'time' ] ] );
        }

        return $response;
    }
}
