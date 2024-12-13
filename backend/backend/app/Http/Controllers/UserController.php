<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function index(){
        $users = User::all();
        return response()->json($users);
    }
    public function rawr(){
        $user = User::where('userName','admin')->first();
        $user->assignRole('admin');
        return 'dale campeon';
    }
    public function show($id){
        $user = User::find($id);
        return response()->json($user);
    }
    public function createUser(Request $request){
        // Definir las reglas de validación
        $validator = Validator::make($request->all(), [
            'userName' => 'required|unique:users',
            'password' => 'required|min:6',
            'userNombres' => 'required',
            'userApellidos' => 'required',
            'userCorreo' => 'required|email',
        ]);
    
        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }
    
        // Si la validación es exitosa, se procede a crear el usuario
        $user = new User();
        $user->userName = $request->userName;
        $user->password = bcrypt($request->password); // Encriptar la contraseña
        $user->userNombres = $request->userNombres;
        $user->userApellidos = $request->userApellidos;
        $user->userCorreo = $request->userCorreo;
        $user->save();
    
        // Asignar rol al usuario
        $user->assignRole('student');
    
        // Retornar la respuesta exitosa en JSON
       return response()->json([
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'user' => $user
        ], 201);  // 201 Created
    }
    public function updateUser(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'userName' => 'required|unique:users,userName,' . $id . ',userId',
            'password' => 'required|min:6',
            'userNombres' => 'required',
            'userApellidos' => 'required',
            'userCorreo' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
        $user->userName = $request->userName;
        $user->password = bcrypt($request->password);
        $user->userNombres = $request->userNombres;
        $user->userApellidos = $request->userApellidos;
        $user->userCorreo = $request->userCorreo;

        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente',
            'user' => $user
        ], 201); 
    }
    public function deleteUser($id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ], 200);
        
    }
    public function getDocentes(){
        $users = User::role('teacher')->paginate(10);
        return response()->json($users);
    }
    public function createDocente(Request $request){
        $validator = Validator::make($request->all(), [
            'userName' => 'required|unique:users',
            'password' => 'required|min:6',
            'userNombres' => 'required',
            'userApellidos' => 'required',
            'userCorreo' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new User();
        $user->userName = $request->userName;
        $user->password = bcrypt($request->password); // Encriptar la contraseña
        $user->userNombres = $request->userNombres;
        $user->userApellidos = $request->userApellidos;
        $user->userCorreo = $request->userCorreo;
        $user->save();
    
        // Asignar rol al usuario
        $user->assignRole('teacher');
    
        // Retornar la respuesta exitosa en JSON
       return response()->json([
            'success' => true,
            'message' => 'Docente creado exitosamente',
            'user' => $user
        ], 201);  // 201 Created
    }
    public function getEstudiantes(){
        $users = User::role('student')->paginate(10);
        return response()->json($users);
    }
    
    
}
