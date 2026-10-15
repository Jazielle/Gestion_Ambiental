<?php

use App\Http\Controllers\EstacionController;
use Illuminate\Support\Facades\Route;

require app_path('Modulos/Incendios/rutasWeb.php');

Route::resource('estaciones', EstacionController::class)
    ->parameters(['estaciones' => 'estacion'])
    ->only(['index', 'create', 'store', 'edit', 'update']);
