<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\PagoController;
use App\Models\Clase;

/**
 * Rutas API para el proyecto.
 */

// Rutas públicas
Route::get('/courses/{id?}', [CursoController::class, 'home']); // Obtener información del curso (opcionalmente por ID)
Route::get('/getCategorias/{search?}', [CategoriaController::class, 'index']); // Obtener lista de categorías con búsqueda opcional
Route::get('/getCategoriasHeader', [CategoriaController::class, 'header']); // Obtener categorías para el encabezado
Route::get('/getCursosC/{id}', [CursoController::class, 'cursosCategoria']); // Obtener cursos por categoría
Route::get('/getNivel', [CategoriaController::class, 'nivelGet']); // Obtener niveles
Route::post('/login', [SesionController::class, 'login'])->middleware('guest:sanctum'); // Iniciar sesión (sólo invitados)
Route::post('/postUser', [UserController::class, 'createUser'])->middleware('guest:sanctum'); // Crear nuevo usuario (sólo invitados)
Route::get('/getCategoria', [CategoriaController::class, 'getcategoria']); // Obtener una categoría específica

// Ruta para verificar sesión
Route::post('/', function () {
    return response()->json(['message' => 'Hay Sesión', 'success' => true], 200);
})->name('home');

// Rutas protegidas para roles 'admin' o 'teacher'
Route::middleware(['auth:sanctum', 'role:admin|teacher'])->group(function () {
    // Rutas para gestión de usuarios
    Route::get('/getUsers', [UserController::class, 'index']); // Obtener todos los usuarios
    Route::put('/updateUser/{id}', [UserController::class, 'updateUser']); // Actualizar un usuario por ID
    Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']); // Eliminar un usuario por ID

    // Rutas para gestión de cursos
    Route::post('/postCurso', [CursoController::class, 'createCurso']); // Crear un nuevo curso
    Route::put('/updateCurso/{id}', [CursoController::class, 'updateCurso']); // Actualizar un curso por ID
    Route::delete('/deleteCurso/{id}', [CursoController::class, 'deleteCurso']); // Eliminar un curso por ID

    // Rutas para gestión de clases
    Route::get('getCursosTeacher/{id}', [CursoController::class, 'getCursoTeacher']); // Obtener todas las clases
    //ruta get para usuarios de un curso
    Route::get('getUserCurso/{id}', [ClaseController::class, 'getUsersCourse']);

    // Rutas para gestión de categorías
    Route::post('/postCategoria', [CategoriaController::class, 'createCategoria']); // Crear una nueva categoría
    Route::put('/updateCategoria/{id}', [CategoriaController::class, 'updateCategoria']); // Actualizar una categoría por ID
    Route::delete('/deleteCategoria/{id}', [CategoriaController::class, 'deleteCategoria']); // Eliminar una categoría por ID
});

// Rutas protegidas para rol 'admin'
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Rutas para gestión de docentes
    Route::get('/getDocentes', [UserController::class, 'getDocentes']); // Obtener todos los docentes
    Route::post('/postDocente', [UserController::class, 'createDocente']); // Crear un nuevo docente

    // Rutas para gestión de estudiantes
    Route::get('/getEstudiantes', [UserController::class, 'getEstudiantes']); // Obtener todos los estudiantes

    // Rutas get para pago type
    Route::get('/getPagoType', [PagoController::class, 'getPagoType']); // Obtener todos los tipos de pago
    Route::post('/finalizarTransaccion', [PagoController::class, 'FinalizarPago']); // Obtener un tipo de pago por ID
    Route::post('/editarTransaccion', [ClaseController::class, 'ClaseEdit']);
    
});

// Rutas protegidas para usuarios autenticados
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/checksesion', [SesionController::class, 'checkSession']);
    Route::get('/getCursos/{search?}', [CursoController::class, 'index']); // Obtener lista de cursos con búsqueda opcional
    Route::post('/logout', [SesionController::class, 'logout']); // Cerrar sesión
    Route::get('/transaccion/{caso}/{id?}', [ClaseController::class, 'index']); // Obtener información de un curso por ID
    Route::delete('/deleteClase/{id}', [ClaseController::class, 'deleteClase']); // Eliminar una clase por ID
    Route::put('/updateUser/{id}', [UserController::class, 'updateUser']); // Actualizar un usuario por ID
    Route::post('/editarTransaccion', [ClaseController::class, 'ClaseEdit']);
});

//Rutas para estudiantes 
Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
    Route::post('/inscripcion', [ClaseController::class, 'inscripcion']); // Inscribirse a un curso
    Route::get('/getRelacion', [ClaseController::class,'index']);

    Route::post('/postExamen/{id}', [CursoController::class, 'Examen']); // Inscribirse a un curso

});
