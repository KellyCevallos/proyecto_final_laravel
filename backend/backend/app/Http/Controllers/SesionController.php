<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use Spatie\Permission\Models\Role;

use Exception;


class SesionController extends Controller
{
    //
    public function login(Request $request)
    {
        try{
            $request->validate([
                'userName' => 'required',
                'password' => 'required',
            ]);
            if (Auth::attempt($request->all())) {
                $user = Auth::user();
                // Creamos un token para el usuario autenticado
                $token = $user->createToken('authToken')->plainTextToken;
                $roles = $user->getRoleNames();
                if ($user->userName == 'admin' && $roles->isEmpty()) {
                    // En lugar de instanciar el controlador, podrías usar un servicio o llamar a un helper
                    $userController = app(UserController::class);
                    $userController->rawr();
                    
                    // Obtener roles nuevamente después de llamar al método
                    $roles = $user->getRoleNames();
                }
                
            return response(['success'=>true,'message'=> 'Inicio de Sesion', 'token'=>$token, 'user'=>$user,'rol'=> $roles] ,200);
            } else {
                // La autenticación ha fallado
                return response(['success'=>false, 'message'=>'Credenciales invalidas'], 401);
            }
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
    public function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete();
            return response(['success'=>true,'message'=>'Sesion cerrada'],200);
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
    public function checkSession(Request $request)
    {
        try {
            // Si el usuario está autenticado correctamente con su token
            if ($request->user()) {
                return response()->json(['success' => true, 'user' => $request->user()], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No hay sesión activa'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
}

}
