<?php

use App\Modulos\Incendios\ControladorIncendios;
use Illuminate\Support\Facades\Route;

Route::get('/', [ControladorIncendios::class, 'mostrarPagina'])->name('incendios.inicio');
Route::get('/incendios', [ControladorIncendios::class, 'mostrarPagina'])->name('incendios.pagina');
