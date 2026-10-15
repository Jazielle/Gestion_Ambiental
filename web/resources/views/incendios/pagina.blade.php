<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta
            name="description"
            content="GeoCruz: consulta y visualización de focos de calor en el departamento de Santa Cruz."
        />
        <title>GeoCruz · Focos de calor</title>
        <link
            rel="icon"
            href="{{ asset('modulos/incendios/geocruz.svg') }}"
            type="image/svg+xml"
        />
        <link
            rel="stylesheet"
            href="{{ asset('modulos/incendios/incendios.css') }}"
        />
    </head>
    <body>
        <a class="salto-contenido" href="#contenido">Ir al monitoreo</a>
        <aside class="barra-lateral" aria-label="Navegación">
            <a class="marca" href="{{ route('incendios.inicio') }}">
                <img
                    src="{{ asset('modulos/incendios/geocruz.svg') }}"
                    width="38"
                    height="38"
                    alt=""
                />
                <span
                    >Geo<span class="marca-acento">Cruz</span
                    ><small>Observación ambiental</small></span
                >
            </a>
            <p class="etiqueta-navegacion">ESPACIO DE TRABAJO</p>
            <a
                class="enlace-activo"
                aria-current="page"
                href="{{ route('incendios.pagina') }}"
            >
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M12 3c2 4 5 5 5 9a5 5 0 0 1-10 0c0-2 1-4 3-6 0 3 1 4 2 4 1-2 1-4 0-7Z"
                    />
                </svg>
                Focos de calor
            </a>
            <div class="territorio-lateral">
                <span class="etiqueta-navegacion">TERRITORIO</span
                ><strong>Santa Cruz</strong><span>Bolivia · Departamento</span>
            </div>
            <div class="pie-lateral">
                <span class="monograma">GC</span>
                <div>
                    <strong>Proyecto GeoCruz</strong
                    ><small>Demo académica</small>
                </div>
            </div>
        </aside>

        <div class="espacio-principal">
            <header class="barra-superior">
                <span
                    >Monitoreo ambiental <span aria-hidden="true">/</span>
                    <strong>Focos de calor</strong></span
                ><span class="insignia-territorio">Santa Cruz, Bolivia</span>
            </header>
            <main id="contenido">
                <section class="encabezado-pagina">
                    <div>
                        <p class="sobre-titulo">OBSERVACIÓN SATELITAL</p>
                        <h1>Focos de calor</h1>
                        <p>
                            Explora las detecciones y consulta su información en
                            el mapa.
                        </p>
                    </div>
                    <button
                        type="button"
                        id="boton-actualizar"
                        class="boton-secundario"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M20 7v5h-5M4 17v-5h5M5 8a8 8 0 0 1 13-2l2 2M4 16l2 2a8 8 0 0 0 13-2"
                            /></svg
                        >Actualizar datos
                    </button>
                </section>

                <div class="aviso-fuente" id="aviso-fuente">
                    <span class="simbolo-aviso" aria-hidden="true">i</span>
                    <div>
                        <strong id="titulo-fuente">Modo demostración</strong
                        ><span id="descripcion-fuente"
                            >Datos simulados para probar la aplicación. No
                            representan incendios reales.</span
                        >
                    </div>
                    <span class="insignia-demo" id="insignia-fuente"
                        >SIMULADO</span
                    >
                </div>

                @include('incendios.componentes.filtros')
                @include('incendios.componentes.resumen')

                <div
                    id="mensaje-error"
                    class="mensaje-error"
                    role="alert"
                    hidden
                ></div>
                <div
                    id="estado-consulta"
                    class="solo-lectores"
                    role="status"
                    aria-live="polite"
                ></div>

                <section
                    class="area-monitoreo"
                    aria-label="Resultados de la consulta"
                >
                    <div class="panel-mapa">
                        <div class="encabezado-panel">
                            <div>
                                <h2>Mapa de detecciones</h2>
                                <p>Departamento de Santa Cruz</p>
                            </div>
                            <span id="cantidad-mapa" class="etiqueta-neutral"
                                >Consultando…</span
                            >
                        </div>
                        <div
                            class="superficie-mapa"
                            id="contenedor-mapa"
                            aria-busy="true"
                        ></div>
                        <div class="pie-mapa">
                            <div class="leyenda">
                                <span><i class="punto alta"></i>Alta</span
                                ><span
                                    ><i class="punto nominal"></i>Nominal</span
                                ><span><i class="punto baja"></i>Baja</span
                                ><small>Confianza de detección</small>
                            </div>
                            <span id="nombre-mapa">Mapa de demostración</span>
                        </div>
                    </div>
                    <aside
                        class="panel-detecciones"
                        aria-label="Listado y detalle de detecciones"
                    >
                        <div class="encabezado-panel">
                            <div>
                                <h2>Detecciones</h2>
                                <p>Más recientes primero</p>
                            </div>
                            <span id="cantidad-lista" class="contador-lista"
                                >—</span
                            >
                        </div>
                        <div id="lista-focos" class="lista-focos">
                            <p class="estado-vacio">Consultando detecciones…</p>
                        </div>
                        @include('incendios.componentes.detalle')
                    </aside>
                </section>
                <footer class="pie-pagina">
                    <p>
                        Un foco de calor es una detección térmica: por sí solo
                        no confirma un incendio.
                    </p>
                    <p>
                        Límites:
                        <a
                            href="https://www.geoboundaries.org/"
                            target="_blank"
                            rel="noreferrer"
                            >geoBoundaries / GeoBolivia</a
                        >
                        · Horarios en Bolivia (UTC−4)
                    </p>
                </footer>
            </main>
        </div>
        <!-- prettier-ignore -->
        <script id="configuracion-incendios" type="application/json">{!! json_encode($configuracionMapa, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
        <script
            type="module"
            src="{{ asset('modulos/incendios/incendios.js') }}"
        ></script>
    </body>
</html>
