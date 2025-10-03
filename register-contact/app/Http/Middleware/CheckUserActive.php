<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Common\ResponseApi;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Exception;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return ResponseApi::unauthorized(__('auth.unauthenticated'));
            }

            $user = auth()->user();

            if (!$user->isActive) {
                return ResponseApi::unauthorized(__('auth.account_not_active'));
            }

            // Check if user is not retired (if emp_status field exists)
            if (isset($user->emp_status) && $user->emp_status === 'retired') {
                return ResponseApi::unauthorized(__('auth.account_retired'));
            }

            // Check token version for JWT token invalidation
            try {
                $token = JWTAuth::getToken();
                if ($token) {
                    $payload = JWTAuth::getPayload($token);
                    $tokenVersion = $payload->get('token_version', 0);
                    
                    // If user has token_version field, check if token is still valid
                    if (isset($user->token_version) && $tokenVersion < $user->token_version) {
                        return ResponseApi::unauthorized(__('auth.token_invalid'));
                    }
                }
            } catch (JWTException $e) {
                // If JWT token is invalid, return unauthorized
                return ResponseApi::unauthorized(__('auth.token_invalid'));
            }

            return $next($request);

        } catch (Exception $e) {
          
        }
    }
}
