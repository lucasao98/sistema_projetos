<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AuthJwt extends BaseMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next){
            $token = $request->bearerToken();

            if($token == null){
                return response()->json(['status'=>'Authorization Token not found']);
            }

            $user = User::where('remember_token',$token)->first();

            if($user){
                if($token == $user->remember_token){
                    return $next($request);
                }
            }else{
                return response()->json(['status'=>'Token is invalid']);
            }
    }
}
