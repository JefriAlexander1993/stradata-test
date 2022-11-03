<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwtauth', ['except' => ['login', 'register']]);
    }

    /*
    |-------------------------------------------------------------------------------
    | Login
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/login
    | Method:         Post
    | Description:    Logueo
    */
    public function login()
    {
        $credentials = request(['email', 'password']);

        $validate = UserModel::getValidator($credentials);

        if ($validate->fails()) {

            return response()->json(
                [
                    'data' => $validate->errors(),
                    'execution_status' => "Los datos ingresados no cumple con lo requerido",
                    "error" => true,
                    'count' => 0,
                    "class" => 'alert alert-danger'
                ],
                400
            );
        }

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'data' => [],
                'message' => 'Algo salio mal, ingresa tus credenciales nuevamente.',
                'error' => true,
                'execution_status' => "No autorizado.",
                "class" => 'alert alert-danger'
            ], 401);
        }
        return response()->json([
            'data' => [$token, auth()->user()->name],
            'message' => 'Te haz autenticado correctamente.',
            'error' => false,
            'execution_status' => "Exitoso.",
            "class" => 'alert alert-success'
        ], 200);


        // return $this->respondWithToken($token);
    }

    /*
    |-------------------------------------------------------------------------------
    | getUser
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/getUser
    | Method:         Post
    | Description:    Se retorna el usuario autenticado.
    */
    public function getUser()
    {
        return response()->json([
            'data' => auth()->user(),
            'message' => 'Te haz autenticado correctamente.',
            'error' => false,
            'execution_status' => "Exitoso.",
            "class" => 'alert alert-success'
        ], 200);
    }

    /*
    |-------------------------------------------------------------------------------
    | Logout
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/logout
    | Method:         Post
    | Description:    Cerrar sessión
    */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'data' => null,
            'message' => 'Se ha cerrado sessión exitosamente.',
            'error' => false,
            'execution_status' => "Exitoso.",
            "class" => 'alert alert-success'
        ], 200);
    }
    /*
    |-------------------------------------------------------------------------------
    | checkToken
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/checkToken
    | Method:         Post
    | Description:    Ficha de verificación.
    */
    public function checkToken()
    {
        return response()->json([
            'data' => auth()->user(),
            'message' => 'Ficha de verificación.',
            'error' => false,
            'execution_status' => "Exitoso.",
            "class" => 'alert alert-success'
        ], 200);
    }
    /*
    |-------------------------------------------------------------------------------
    | register
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/register
    | Method:         Post
    | Description:    Registar nuevos usuarios
    */
    protected function register(Request $request)
    {

        $validate = UserModel::getValidator($request->all(), 'register');

        if ($validate->fails()) {

            return response()->json(
                [
                    'data' => $validate->errors(),
                    'execution_status' => "Los datos ingresados no cumple con lo requerido",
                    "error" => true,
                    'count' => 0,
                    "class" => 'alert alert-danger'
                ],
                400
            );
        }

        $user = UserModel::create($request);


        return response()->json([
            'data' =>$user,
            'message' => 'Se ha creado exitosamente el usuario.',
            'error' => false,
            'execution_status' => "Exitoso.",
            "class" => 'alert alert-success', 200
        ]);
    }
}
