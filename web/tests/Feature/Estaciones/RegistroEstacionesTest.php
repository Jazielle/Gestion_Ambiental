<?php

namespace Tests\Feature\Estaciones;

use App\Models\Estacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroEstacionesTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_el_estado_vacio_y_el_formulario(): void
    {
        $this->get('/estaciones')->assertOk()->assertSee('No hay estaciones registradas');
        $this->get('/estaciones/create')->assertOk()->assertSee('Registrar estación');
    }

    public function test_registra_y_lista_una_estacion_persistida(): void
    {
        $this->post('/estaciones', ['nombre' => 'Estación Centro', 'latitud' => -17.7833, 'longitud' => -63.1821])
            ->assertRedirect('/estaciones')->assertSessionHas('estado');
        $this->assertDatabaseHas('estaciones', ['nombre' => 'Estación Centro', 'latitud' => -17.7833, 'longitud' => -63.1821]);
        $this->get('/estaciones')->assertOk()->assertSee('Estación Centro')->assertSee('-17.7833000')->assertSee('-63.1821000');
    }

    public function test_exige_los_tres_campos_incluso_con_espacios(): void
    {
        $this->from('/estaciones/create')->post('/estaciones', ['nombre' => '   '])
            ->assertRedirect('/estaciones/create')->assertSessionHasErrors(['nombre', 'latitud', 'longitud']);
        $this->assertDatabaseCount('estaciones', 0);
    }

    public function test_rechaza_coordenadas_no_numericas_o_fuera_de_rango(): void
    {
        foreach ([['latitud' => 91, 'longitud' => 181], ['latitud' => -91, 'longitud' => -181], ['latitud' => 'texto', 'longitud' => ['dato']]] as $coordenadas) {
            $this->post('/estaciones', ['nombre' => 'Estación inválida'] + $coordenadas)->assertSessionHasErrors(['latitud', 'longitud']);
        }
        $this->assertDatabaseCount('estaciones', 0);
    }

    public function test_acepta_cero_y_los_limites_geograficos(): void
    {
        foreach ([[0, 0], [90, 180], [-90, -180]] as [$latitud, $longitud]) {
            $this->post('/estaciones', compact('latitud', 'longitud') + ['nombre' => 'Estación de prueba'])->assertSessionHasNoErrors();
        }
        $this->assertDatabaseCount('estaciones', 3);
    }

    public function test_edita_solo_la_estacion_seleccionada(): void
    {
        $a = Estacion::create(['nombre' => 'Centro', 'latitud' => -17, 'longitud' => -63]);
        $b = Estacion::create(['nombre' => 'Norte', 'latitud' => -16, 'longitud' => -62]);
        $this->get('/estaciones/'.$a->id.'/edit')->assertOk()->assertSee('Centro')->assertSee('Guardar cambios');
        $this->put('/estaciones/'.$a->id, ['nombre' => 'Centro actualizado', 'latitud' => -17.5, 'longitud' => -63.2])
            ->assertRedirect('/estaciones')->assertSessionHasNoErrors();
        $this->assertDatabaseHas('estaciones', ['id' => $a->id, 'nombre' => 'Centro actualizado', 'latitud' => -17.5]);
        $this->assertSame('Norte', $b->fresh()->nombre);
        $this->assertDatabaseCount('estaciones', 2);
    }

    public function test_una_edicion_invalida_conserva_los_datos_originales(): void
    {
        $estacion = Estacion::create(['nombre' => 'Centro', 'latitud' => -17, 'longitud' => -63]);
        $this->put('/estaciones/'.$estacion->id, ['nombre' => '', 'latitud' => 100, 'longitud' => 'no'])
            ->assertSessionHasErrors(['nombre', 'latitud', 'longitud']);
        $this->assertSame('Centro', $estacion->fresh()->nombre);
    }

    public function test_no_crea_una_estacion_al_editar_un_identificador_inexistente(): void
    {
        $this->get('/estaciones/999/edit')->assertNotFound();
        $this->put('/estaciones/999', ['nombre' => 'Nueva', 'latitud' => 0, 'longitud' => 0])->assertNotFound();
        $this->assertDatabaseCount('estaciones', 0);
    }

    public function test_escapa_html_y_pagina_el_listado(): void
    {
        Estacion::create(['nombre' => '<script>alert(1)</script>', 'latitud' => 0, 'longitud' => 0]);
        for ($i = 0; $i < 15; $i++) {
            Estacion::create(['nombre' => 'Estación '.$i, 'latitud' => -17, 'longitud' => -63]);
        }
        $this->get('/estaciones')->assertOk()->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertSee('Siguiente');
        $this->get('/estaciones?page=2')->assertOk()->assertSee('Anterior');
    }
}
