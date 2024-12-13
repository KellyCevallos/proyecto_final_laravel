<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Nivel;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    // Obtener todas las categorías, con búsqueda opcional
    public function index($search = null)
    {
        if ($search) {
            // Filtrar categorías que coincidan con la búsqueda
            $categorias = Categoria::where('categoriaName', 'like', '%' . $search . '%')->paginate(10);
        } else {
            // Obtener todas las categorías paginadas
            $categorias = Categoria::paginate(10);
        }
        return response()->json($categorias);
    }

    // Obtener todas las categorías sin paginación ni búsqueda
    public function getcategoria()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    // Obtener categorías que tienen cursos asociados
    public function header()
    {
        $categoriasConCursos = Categoria::whereHas('cursos')->get();
        return response()->json($categoriasConCursos);
    }

    // Crear una nueva categoría
    public function createCategoria(Request $request)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'categoriaName' => 'required|unique:categorias',
            'categoriaDescripcion' => 'required',
            'categoriaImagen' => 'required'
        ]);

        // Si la validación falla, devolver errores
        return $validator->fails()
            ? response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
              ], 422)
            : response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'data' => tap(new Categoria(), function ($categoria) use ($request) {
                    // Asignar los datos y guardar la nueva categoría
                    $categoria->categoriaName = $request->categoriaName;
                    $categoria->categoriaDescripcion = $request->categoriaDescripcion;
                    $categoria->categoriaImagen = $request->categoriaImagen;
                    $categoria->save();
                })
              ]);
    }

    // Actualizar una categoría existente
    public function updateCategoria(Request $request, $id)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'categoriaName' => 'required|unique:categorias',
            'categoriaDescripcion' => 'required',
            'categoriaImagen' => 'required'
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar la categoría por ID
        $categoria = Categoria::find($id);
        if ($categoria) {
            // Actualizar los datos de la categoría
            $categoria->categoriaName = $request->categoriaName;
            $categoria->categoriaDescripcion = $request->categoriaDescripcion;
            $categoria->save();
            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente',
                'data' => $categoria
            ]);
        } else {
            // Devolver error si la categoría no existe
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
    }

    // Eliminar una categoría
    public function deleteCategoria($id)
    {
        // Buscar la categoría por ID
        $categoria = Categoria::find($id);

        if ($categoria) {
            // Eliminar la categoría si existe
            $categoria->delete();
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada exitosamente',
                'data' => $categoria
            ]);
        } else {
            // Devolver error si la categoría no existe
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
    }

    // Obtener todos los niveles
    public function nivelGet()
    {
        $niveles = Nivel::all();
        return response()->json($niveles);
    }
}
