<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\Validator;

class CursoController extends Controller
{
    // Obtener lista de cursos con búsqueda opcional o por categoría
    public function index(Request $request, $search = null)
    {
        try {
            if ($search) {
                // Buscar cursos cuyo nombre coincida con el término de búsqueda
                $cursos = Curso::where('cursoName', 'like', '%' . $search . '%')->paginate(10);
            } else if ($request->has('categoriaId')) {
                // Buscar cursos por ID de categoría
                $cursos = Curso::where('cursoCategoriaId', $request->categoriaId)->paginate(10);
            } else {
                // Obtener todos los cursos paginados
                $cursos = Curso::orderBy('cursoId', 'asc')->paginate(10);
            }
            return response()->json($cursos);
        } catch (\Exception $e) {
            // Manejar errores y devolver respuesta con mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los cursos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Obtener cursos por ID de categoría
    public function cursosCategoria($id)
    {
        try {
            $cursos = Curso::where('cursoCategoriaId', $id)->paginate(10);
            return response()->json($cursos);
        } catch (\Exception $e) {
            // Manejar errores y devolver respuesta con mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los cursos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    //get para obtener los cursos del profesor
    public function getCursoTeacher($id)
    {
        try {
            $cursos = Curso::where('createdBy', $id)->paginate(10);
            return response()->json($cursos);
        } catch (\Exception $e) {
            // Manejar errores y devolver respuesta con mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los cursos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // Crear un nuevo curso
    public function createCurso(Request $request)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'cursoName' => 'required',
            'cursoDescripcion' => 'required',
            'cursoNivelId' => 'required',
            'cursoValor' => 'required',
            'cursoRequisito' => 'required',
            'cursoContenido' => 'required',
            'createdBy' => 'required',
            'cursoCategoriaId' => 'required',
            'cursoExamen' => 'required'
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear y guardar el nuevo curso
        $curso = new Curso();
        $curso->cursoName = $request->cursoName;
        $curso->cursoDescripcion = $request->cursoDescripcion;
        $curso->cursoNivelId = $request->cursoNivelId;
        $curso->cursoValor = $request->cursoValor;
        $curso->cursoRequisito = $request->cursoRequisito;
        $curso->cursoContenido = $request->cursoContenido;
        $curso->createdBy = $request->createdBy;
        $curso->cursoCategoriaId = $request->cursoCategoriaId;
        $curso->cursoExamen = $request->cursoExamen;
        $curso->save();

        return response()->json([
            'success' => true,
            'message' => 'Curso creado exitosamente'
        ]);
    }

    // Actualizar un curso existente
    public function updateCurso(Request $request, $id)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'cursoName' => 'required',
            'cursoDescripcion' => 'required',
            'cursoNivelId' => 'required',
            'cursoValor' => 'required',
            'cursoRequisito' => 'required',
            'cursoContenido' => 'required',
            'cursoCategoriaId' => 'required'
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar el curso por ID
        $curso = Curso::find($id);
        if ($curso) {
            // Actualizar los datos del curso
            $curso->cursoName = $request->cursoName;
            $curso->cursoDescripcion = $request->cursoDescripcion;
            $curso->cursoNivelId = $request->cursoNivelId;
            $curso->cursoValor = $request->cursoValor;
            $curso->cursoRequisito = $request->cursoRequisito;
            $curso->cursoContenido = $request->cursoContenido;
            $curso->createdBy = $request->createdBy;
            $curso->cursoCategoriaId = $request->cursoCategoriaId;
            $curso->save();
            return response()->json([
                'success' => true,
                'message' => 'Curso actualizado exitosamente'
            ]);
        }
        // Devolver error si el curso no existe
        return response()->json([
            'success' => false,
            'message' => 'Curso no encontrado'
        ], 404);
    }

    // Eliminar un curso
    public function deleteCurso($id)
    {
        // Buscar el curso por ID
        $curso = Curso::find($id);
        if ($curso) {
            // Eliminar el curso si existe
            $curso->delete();
            return response()->json([
                'success' => true,
                'message' => 'Curso eliminado exitosamente'
            ]);
        }
        // Devolver error si el curso no existe
        return response()->json([
            'success' => false,
            'message' => 'Curso no encontrado'
        ], 404);
    }

    // Obtener información de un curso o los últimos 10 cursos creados
    public function home($id = null)
    {
        try {
            if ($id) {
                // Obtener un curso específico con su categoría y nivel
                $curso = Curso::with([
                    'categoria:categoriaId,categoriaName,categoriaImagen', // Trae solo categoriaId, categoriaName y categoriaImagen de la tabla categorías
                    'nivel:nivelId,nivelName' // Trae solo nivelId y nivelName de la tabla niveles
                ])->find($id);

                return response()->json([
                    'success' => true,
                    'data' => $curso
                ]);
            } else {
                // Obtener los últimos 10 cursos creados con su categoría y nivel
                $cursos = Curso::with([
                    'categoria:categoriaId,categoriaName,categoriaImagen', // Trae solo categoriaId, categoriaName y categoriaImagen de la tabla categorías
                    'nivel:nivelId,nivelName' // Trae solo nivelId y nivelName de la tabla niveles
                ])->latest()->take(10)->get();

                return response()->json($cursos);
            }
        } catch (\Exception $e) {
            // Manejar errores y devolver respuesta con mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los cursos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function Examen(Request $request, $id)
    {
        // Obtener el curso por el id
        $curso = Curso::find($id);

        // Obtener el examen del curso (almacenado como JSON)
        $examen = json_decode($curso->cursoExamen, true);

        // Obtener las respuestas del usuario
        $respuestasUsuario = $request->all();

        // Inicializar variables para el conteo
        $preguntasCorrectas = 0;
        $totalPreguntas = count($examen);

        // Iterar sobre las preguntas del examen y comparar con las respuestas del usuario
        foreach ($examen as $preguntaServidor) {
            foreach ($respuestasUsuario as $preguntaUsuario) {
                // Comparar las preguntas por el campo "pregunta" y luego las respuestas
                if (
                    $preguntaServidor['pregunta'] === $preguntaUsuario['pregunta'] &&
                    $preguntaServidor['respuestaCorrecta'] === $preguntaUsuario['respuestaCorrecta']
                ) {
                    $preguntasCorrectas++;
                }
            }
        }

        // Calcular la calificación final
        $calificacion = ($preguntasCorrectas / $totalPreguntas) * 10;

        // Devolver el resultado
        return response()->json([
            'request' => $respuestasUsuario,
            'examen' => $examen,
            'preguntasCorrectas' => $preguntasCorrectas,
            'totalPreguntas' => $totalPreguntas,
            'calificacion' => $calificacion,
        ]);
    }

}