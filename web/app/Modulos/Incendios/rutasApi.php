<?php

use App\Modulos\Incendios\ControladorIncendios;
use Illuminate\Support\Facades\Route;

// API pública de lectura: la web y la futura app móvil consumen los mismos datos.
Route::get('/incendios', [ControladorIncendios::class, 'consultarFocos'])->name('incendios.focos');
Route::get('/incendios/limite', [ControladorIncendios::class, 'consultarLimite'])->name('incendios.limite');
