<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductosController;
use Illuminate\Support\Facades\DB;

Route::get("/", [ProductosController::class, "index"])->name("crud.index");

//Ruta para registrar una pelicula
Route::post("/registrar-producto", [ProductosController::class, "create"])->name("crud.create");
//Ruta para actualizar una pelicula
Route::post("/modificar-producto", [ProductosController::class, "update"])->name("crud.update");
//Ruta para eliminar una pelicula
Route::get("/eliminar-producto-{id}", [ProductosController::class, "delete"])->name("crud.delete");

Route::post('/buscar', [ProductosController::class, 'buscar'])->name('buscar');