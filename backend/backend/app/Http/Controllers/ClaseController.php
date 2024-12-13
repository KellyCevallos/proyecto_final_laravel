<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clase;
use Illuminate\Support\Facades\Validator;
use App\Models\Pago;

class ClaseController extends Controller
{
    public function index($caso, $id = null)
    {
        switch ($caso) {
            case 1:
                //se hace el get de todas las clases paginadas donde el campo relVerificacion sea false
                $clases = Clase::with(['curso', 'usuario'])
                               ->where('relVerificacion', false)
                               ->paginate(10);
                return response()->json((object)['success'=> true ,'data' => $clases], 200);
                break;
            case 2:
                //se hace el get de las clases pertenecientes a un usuario y que no esten aprobadas y que el campo relVerificacion sea true
                $clases = Clase::with(['curso', 'usuario'])
                                ->where('users_userId', $id)
                                ->where('relAprobado', false)
                                ->where('relVerificacion', true)
                                ->paginate(10);
                return response()->json((object)['success'=> true ,'data' => $clases], 200);
                break;
            case 3:
                //se hace el get de las clases pertenecientes a un usuario y que esten aprobadas y que el campo relVerificacion sea true
                $clases = Clase::with(['curso', 'usuario'])
                                ->where('users_userId', $id)
                                ->where('relAprobado', true)
                                ->where('relVerificacion', true)
                                ->paginate(10);
                return response()->json((object)['success'=> true ,'data' => $clases], 200);
                break;
            case 4:
                //se hace el get de las clases pertenecientes a un usuario y que el campo relVerificacion sea false
                $clases = Clase::with(['curso', 'usuario'])
                                ->where('users_userId', $id)
                                ->where('relVerificacion', false)
                                ->paginate(10);
                return response()->json((object)['success'=> true ,'data' => $clases], 200);
                break;
            //se hace el get de todas las clases que esten verificadas
            case 5:
                $clases = Clase::with(['curso', 'usuario','pago'])
                                ->where('relVerificacion', true)
                               ->paginate(10);
                return response()->json((object)['success'=> true ,'data' => $clases], 200);
                break;
            default:
                return response()->json((object)['success'=> false, 'message' => 'Opción "caso" inválida'], 400);
        }
    }
    public function inscripcion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'cursoId' => 'required|integer',
            'caso' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Por favor revise los campos', 'errors' => $validator->errors()], 422);
        }

        // Convertir los valores a enteros explícitamente
        $userId = (int) $request->input('userId');
        $cursoId = (int) $request->input('cursoId');
        $caso = (int) $request->input('caso');

        $existingInscription = Clase::where('users_userId', $userId)
                                    ->where('curso_cursoId', $cursoId)
                                    ->first();
        
        if ($existingInscription) {
            return response()->json(['success' => false, 'message' => 'Ya tiene una solicitud pendiente o ya se encuentra registrado en este curso'], 400);
        }

        switch ($caso) {
            case 1:
                $clase = new Clase();
                $clase->users_userId = $userId;
                $clase->curso_cursoId = $cursoId;
                $clase->relVerificacion = true;
                $clase->save();
                return response()->json(['success' => true, 'message' => 'Se ha inscrito a este curso'], 201);
                break;

            case 2:
                $clase = new Clase();
                $clase->users_userId = $userId;
                $clase->curso_cursoId = $cursoId;
                $clase->save();
                return response()->json(['success' => true, 'message' => 'Se ha creado la solicitud, por favor cancele el valor del curso para finalizar la inscripción'], 201);
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Opción "caso" inválida'], 400);
        }

    }
    public function deleteClase($id)
    {
        $clase = Clase::find($id);
        if ($clase) {
            if ($clase->pagoId) {
                $pago = Pago::find($clase->pagoId);
                if ($pago) {
                    $pago->delete();
                }
            }
            $clase->delete();
            return response()->json(['success' => true, 'message' => 'Clase y pago relacionados eliminados'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Clase no encontrada'], 404);
        }
    }
    public function getUsersCourse($id){
        $clases = Clase::with(['usuario'])
                        ->where('curso_cursoId', $id)
                        ->get();
        return response()->json(['success' => true, 'data' => $clases], 200);
    }
    //clase edit 
    public function claseEdit(Request $request){
        switch($request -> caso){
            case 1:

                $validator = Validator::make($request->all(), [
                    'claseId' => 'required|integer',
                    'pagoId' => 'required|integer',
                ]);
                if ($validator->fails()) {
                    return response()->json(['success' => false, 'message' => 'Por favor revise los campos', 'errors' => $validator->errors()], 422);
                }

                $clase = Clase::find($request->claseId);
                $clase->pagoId = $request->pagoId;
                $clase->relVerificacion = true;
                $clase->save();
                return response()->json(['success' => true, 'message' => 'Pago Finalizado'], 200);
                break;
            case 2:
                $validator = Validator::make($request->all(), [
                    'claseId' => 'required|integer',
                    'nota' => 'required|integer'
                ]);
                if ($validator->fails()) {
                    return response()->json(['success' => false, 'message' => 'Por favor revise los campos', 'errors' => $validator->errors()], 422);
                }
                $clase = Clase::find($request->claseId);
                $clase->relNota = $request->nota;
                $clase->relAprobado = true;
                $clase->save();

                return response()->json(['success' => true, 'message' => 'Nota Actualizada'], 200);
                
                break;

        }
    }
    
    

}
