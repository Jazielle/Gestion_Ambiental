<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarEstacionRequest;
use App\Models\Estacion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EstacionController extends Controller
{
    public function index(): View
    {
        return view('estaciones.index', ['estaciones' => Estacion::orderBy('nombre')->orderBy('id')->paginate(15)]);
    }

    public function create(): View
    {
        return view('estaciones.formulario', ['estacion' => new Estacion]);
    }

    public function store(GuardarEstacionRequest $request): RedirectResponse
    {
        Estacion::create($request->validated());

        return to_route('estaciones.index')->with('estado', 'Estación registrada correctamente.');
    }

    public function edit(Estacion $estacion): View
    {
        return view('estaciones.formulario', compact('estacion'));
    }

    public function update(GuardarEstacionRequest $request, Estacion $estacion): RedirectResponse
    {
        $estacion->update($request->validated());

        return to_route('estaciones.index')->with('estado', 'Estación actualizada correctamente.');
    }
}
