<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if($e instanceof TokenExpiredException){
                $newToken = JWTAuth::parseToken()->refresh();
                return response()->json(['error'=>true,'token'=>$newToken,'message'=>'Expired'],200);

            }else if($e instanceof TokenInvalidException){

                return response()->json(['success'=>false,'message'=>'Token expired'],401);

            }else{

                return response()->json(['success'=>false,'message'=>'Token not found'],401);

            }
        }
        return $next($request);
    }
}
