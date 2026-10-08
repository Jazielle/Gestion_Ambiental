<?php

namespace Tests\Feature\Incendios;

use App\Modulos\Incendios\GeografiaSantaCruz;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ConsultaIncendiosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-10-08T12:00:00Z'));
        Http::preventStrayRequests();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
        parent::tearDown();
    }

    public function test_la_pagina_muestra_el_modulo_sin_exponer_la_clave_de_nasa(): void
    {
        config(['incendios.clave_nasa' => 'clave-privada-de-prueba']);
        $this->get('/incendios')->assertOk()->assertSee('GeoCruz')->assertSee('Modo demostración')->assertDontSee('clave-privada-de-prueba');
    }

    public function test_la_demo_devuelve_solo_focos_en_santa_cruz_y_en_las_ultimas_24_horas(): void
    {
        $respuesta = $this->getJson('/api/incendios?dias=1&fuente=demo')->assertOk()->assertJsonPath('consulta.simulados', true);
        $focos = $respuesta->json('focos');
        $this->assertNotEmpty($focos);
        $geografia = app(GeografiaSantaCruz::class);

        foreach ($focos as $foco) {
            $this->assertTrue($geografia->contieneCoordenada($foco['latitud'], $foco['longitud']));
            $this->assertTrue(CarbonImmutable::parse($foco['fechaDeteccion'])->betweenIncluded(CarbonImmutable::now('UTC')->subDay(), CarbonImmutable::now('UTC')));
        }
        $this->assertSame(count($focos), $respuesta->json('resumen.total'));
    }

    public function test_el_periodo_de_siete_dias_amplia_los_resultados(): void
    {
        $totalDia = $this->getJson('/api/incendios?dias=1')->json('resumen.total');
        $totalSemana = $this->getJson('/api/incendios?dias=7')->json('resumen.total');
        $this->assertGreaterThan($totalDia, $totalSemana);
    }

    public function test_rechaza_parametros_invalidos_y_arreglos(): void
    {
        foreach (['dias=2', 'dias=0', 'dias[]=1', 'fuente=otra', 'fuente[]=demo', 'fuente='] as $consulta) {
            $this->getJson('/api/incendios?'.$consulta)->assertStatus(422)->assertJsonStructure(['mensaje']);
        }
    }

    public function test_la_consulta_real_sin_clave_no_devuelve_datos_simulados(): void
    {
        config(['incendios.clave_nasa' => '']);
        $this->getJson('/api/incendios?fuente=nasa')->assertStatus(503)->assertJsonMissingPath('focos');
        Http::assertNothingSent();
    }

    public function test_la_api_expone_el_limite_departamental(): void
    {
        $this->getJson('/api/incendios/limite')->assertOk()->assertJsonPath('properties.shapeName', 'Santa Cruz');
    }

    public function test_nasa_normaliza_fechas_utc_descarta_duplicados_fuera_del_departamento_y_fuera_del_periodo(): void
    {
        config(['incendios.clave_nasa' => 'prueba']);
        $contenidoCsv = "latitude,longitude,acq_date,acq_time,satellite,instrument,confidence,frp\n"
            ."-16.35,-61.10,2026-10-08,35,N,VIIRS,h,36.2\n"
            ."-16.35,-61.10,2026-10-08,35,N,VIIRS,h,36.2\n"
            ."-16.5,-68.15,2026-10-08,1100,N,VIIRS,n,10\n"
            ."-16.35,-61.10,2026-10-06,1100,N,VIIRS,n,10\n"
            ."-16.35,-61.10,2026-10-09,1100,N,VIIRS,n,10\n"
            ."invalido,-61.10,2026-10-08,1100,N,VIIRS,n,10\n";
        Http::fake(['firms.modaps.eosdis.nasa.gov/*' => Http::response($contenidoCsv)]);
        $respuesta = $this->getJson('/api/incendios?fuente=nasa&dias=1')->assertOk()->assertJsonCount(1, 'focos')->assertJsonPath('consulta.simulados', false);
        $this->assertSame('2026-10-08T00:35:00+00:00', $respuesta->json('focos.0.fechaDeteccion'));
        $this->assertSame('alta', $respuesta->json('focos.0.confianza'));
    }

    public function test_siete_dias_se_consultan_en_bloques_validos_y_se_usa_cache(): void
    {
        config(['incendios.clave_nasa' => 'prueba']);
        Http::fake(['firms.modaps.eosdis.nasa.gov/*' => Http::response("latitude,longitude,acq_date,acq_time,confidence\n")]);
        $this->getJson('/api/incendios?fuente=nasa&dias=7')->assertOk()->assertJsonCount(0, 'focos');
        Http::assertSentCount(2);
        Http::assertSent(fn ($solicitud) => str_ends_with($solicitud->url(), '/5/2026-10-01'));
        Http::assertSent(fn ($solicitud) => str_ends_with($solicitud->url(), '/3/2026-10-06'));
        $this->getJson('/api/incendios?fuente=nasa&dias=7')->assertOk();
        Http::assertSentCount(2);
    }

    public function test_un_csv_invalido_de_nasa_produce_un_error_claro(): void
    {
        config(['incendios.clave_nasa' => 'prueba']);
        Http::fake(['firms.modaps.eosdis.nasa.gov/*' => Http::response('Invalid MAP_KEY.')]);
        $this->getJson('/api/incendios?fuente=nasa')->assertStatus(503)->assertJsonMissingPath('focos');
    }

    public function test_la_caida_del_servicio_nasa_no_se_oculta_con_datos_simulados(): void
    {
        config(['incendios.clave_nasa' => 'prueba']);
        Http::fake(['firms.modaps.eosdis.nasa.gov/*' => Http::response('No disponible', 500)]);
        $this->getJson('/api/incendios?fuente=nasa')->assertStatus(503)->assertJsonMissingPath('focos');
    }

    public function test_el_poligono_distingue_santa_cruz_de_otros_departamentos(): void
    {
        $geografia = app(GeografiaSantaCruz::class);
        $this->assertTrue($geografia->contieneCoordenada(-17.78, -63.18));
        $this->assertFalse($geografia->contieneCoordenada(-16.5, -68.15));
        $this->assertFalse($geografia->contieneCoordenada(0, 0));
    }
}
