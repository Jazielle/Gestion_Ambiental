import { colorConfianza, nombreConfianza } from './formato.js';

const espacioSvg = 'http://www.w3.org/2000/svg';
const anchoMapa = 960;
const altoMapa = 610;

function crearElementoSvg(nombre, atributos = {}) {
    const elemento = document.createElementNS(espacioSvg, nombre);
    Object.entries(atributos).forEach(([atributo, valor]) =>
        elemento.setAttribute(atributo, valor),
    );
    return elemento;
}

export function crearMapaDemostracion(contenedor, limite, alSeleccionar) {
    contenedor.replaceChildren();
    const poligonos =
        limite.geometry.type === 'Polygon'
            ? [limite.geometry.coordinates]
            : limite.geometry.coordinates;
    const coordenadas = poligonos.flatMap((poligono) => poligono[0]);
    const longitudes = coordenadas.map((coordenada) => coordenada[0]);
    const latitudes = coordenadas.map((coordenada) => coordenada[1]);
    const oeste = Math.min(...longitudes);
    const este = Math.max(...longitudes);
    const sur = Math.min(...latitudes);
    const norte = Math.max(...latitudes);
    const correccionLongitud = Math.cos((((norte + sur) / 2) * Math.PI) / 180);
    const escala = Math.min(
        (anchoMapa - 130) / ((este - oeste) * correccionLongitud),
        (altoMapa - 100) / (norte - sur),
    );
    const margenHorizontal =
        (anchoMapa - (este - oeste) * correccionLongitud * escala) / 2;
    const margenVertical = (altoMapa - (norte - sur) * escala) / 2;

    function proyectar(longitud, latitud) {
        return [
            margenHorizontal + (longitud - oeste) * correccionLongitud * escala,
            margenVertical + (norte - latitud) * escala,
        ];
    }

    const pantallaPequena = window.matchMedia('(max-width: 720px)');
    function obtenerVistaInicial() {
        return pantallaPequena.matches
            ? [
                  margenHorizontal - 40,
                  0,
                  anchoMapa - 2 * margenHorizontal + 80,
                  altoMapa,
              ]
            : [0, 0, anchoMapa, altoMapa];
    }
    const dibujo = crearElementoSvg('svg', {
        viewBox: obtenerVistaInicial().join(' '),
        'aria-label':
            'Mapa de demostración del departamento de Santa Cruz. Selecciona un foco para ver su detalle.',
    });
    dibujo.classList.add('mapa-demostracion');
    const cuadricula = crearElementoSvg('g', {
        class: 'cuadricula-mapa',
        'aria-hidden': 'true',
    });
    for (let longitud = Math.ceil(oeste); longitud <= este; longitud++) {
        const [posicionHorizontal] = proyectar(longitud, norte);
        cuadricula.append(
            crearElementoSvg('line', {
                x1: posicionHorizontal,
                y1: 0,
                x2: posicionHorizontal,
                y2: altoMapa,
            }),
        );
        const etiqueta = crearElementoSvg('text', {
            x: posicionHorizontal + 5,
            y: 24,
        });
        etiqueta.textContent = `${Math.abs(longitud)}° O`;
        cuadricula.append(etiqueta);
    }
    for (let latitud = Math.ceil(sur); latitud <= norte; latitud++) {
        const [, posicionVertical] = proyectar(oeste, latitud);
        cuadricula.append(
            crearElementoSvg('line', {
                x1: 0,
                y1: posicionVertical,
                x2: anchoMapa,
                y2: posicionVertical,
            }),
        );
        const etiqueta = crearElementoSvg('text', {
            x: 14,
            y: posicionVertical - 7,
        });
        etiqueta.textContent = `${Math.abs(latitud)}° S`;
        cuadricula.append(etiqueta);
    }
    dibujo.append(cuadricula);

    const capaLimite = crearElementoSvg('g', { 'aria-hidden': 'true' });
    poligonos.forEach((poligono) => {
        const trazado = poligono
            .map(
                (anillo) =>
                    anillo
                        .map(
                            (coordenada, indice) =>
                                `${indice ? 'L' : 'M'}${proyectar(...coordenada).join(',')}`,
                        )
                        .join(' ') + ' Z',
            )
            .join(' ');
        capaLimite.append(
            crearElementoSvg('path', {
                d: trazado,
                class: 'limite-departamento',
                'fill-rule': 'evenodd',
            }),
        );
    });
    dibujo.append(capaLimite);

    const ciudades = [
        ['Santa Cruz de la Sierra', -63.18, -17.78, 'Santa Cruz'],
        ['San Ignacio de Velasco', -60.96, -16.38, 'San Ignacio'],
        ['Concepción', -62.03, -16.13, 'Concepción'],
        ['San José de Chiquitos', -60.74, -17.85, 'San José'],
        ['Puerto Suárez', -57.73, -18.96, 'Puerto Suárez'],
        ['Camiri', -63.52, -20.04, 'Camiri'],
    ];
    ciudades.forEach(([nombre, longitud, latitud, nombreCorto]) => {
        const [posicionHorizontal, posicionVertical] = proyectar(
            longitud,
            latitud,
        );
        const alBordeDerecho = longitud > este - 1;
        const etiqueta = crearElementoSvg('text', {
            x: posicionHorizontal + (alBordeDerecho ? -9 : 9),
            y: posicionVertical + 4,
            class: 'nombre-ciudad',
            'aria-hidden': 'true',
            'text-anchor': alBordeDerecho ? 'end' : 'start',
            'data-nombre': nombre,
            'data-nombre-corto': nombreCorto,
        });
        etiqueta.textContent = pantallaPequena.matches ? nombreCorto : nombre;
        dibujo.append(
            crearElementoSvg('circle', {
                cx: posicionHorizontal,
                cy: posicionVertical,
                r: 3,
                class: 'punto-ciudad',
                'aria-hidden': 'true',
            }),
            etiqueta,
        );
    });
    const capaFocos = crearElementoSvg('g');
    dibujo.append(capaFocos);
    contenedor.append(dibujo);

    let acercamiento = 1;
    function actualizarVista() {
        const [inicioHorizontal, inicioVertical, anchoVista, altoVista] =
            obtenerVistaInicial();
        dibujo.setAttribute(
            'viewBox',
            `${inicioHorizontal + (anchoVista - anchoVista / acercamiento) / 2} ${inicioVertical + (altoVista - altoVista / acercamiento) / 2} ${anchoVista / acercamiento} ${altoVista / acercamiento}`,
        );
        dibujo.querySelectorAll('.nombre-ciudad').forEach((etiqueta) => {
            etiqueta.textContent = pantallaPequena.matches
                ? etiqueta.dataset.nombreCorto
                : etiqueta.dataset.nombre;
        });
    }
    pantallaPequena.addEventListener('change', actualizarVista);
    const controles = document.createElement('div');
    controles.className = 'controles-mapa';
    [
        ['+', 'Acercar mapa', 0.3],
        ['−', 'Alejar mapa', -0.3],
    ].forEach(([texto, etiqueta, incremento]) => {
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.textContent = texto;
        boton.setAttribute('aria-label', etiqueta);
        boton.addEventListener('click', () => {
            acercamiento = Math.max(
                1,
                Math.min(2.5, acercamiento + incremento),
            );
            actualizarVista();
        });
        controles.append(boton);
    });
    const botonRestablecer = document.createElement('button');
    botonRestablecer.type = 'button';
    botonRestablecer.textContent = 'Ver departamento';
    botonRestablecer.className = 'boton-restablecer-mapa';
    botonRestablecer.addEventListener('click', () => {
        acercamiento = 1;
        actualizarVista();
    });
    contenedor.append(controles, botonRestablecer);

    return {
        mostrarFocos(focos, identificadorSeleccionado) {
            capaFocos.replaceChildren();
            focos.forEach((foco) => {
                const [posicionHorizontal, posicionVertical] = proyectar(
                    foco.longitud,
                    foco.latitud,
                );
                const seleccionado =
                    foco.identificador === identificadorSeleccionado;
                const marcador = crearElementoSvg('g', {
                    transform: `translate(${posicionHorizontal} ${posicionVertical})`,
                    tabindex: '0',
                    role: 'button',
                    'aria-pressed': String(seleccionado),
                    'aria-label': `Foco ${foco.identificador}, confianza ${nombreConfianza(foco.confianza)}`,
                    class: 'marcador-foco',
                });
                marcador.append(
                    crearElementoSvg('circle', {
                        r: seleccionado ? 18 : 13,
                        fill: colorConfianza(foco.confianza),
                        opacity: seleccionado ? '.25' : '.13',
                    }),
                    crearElementoSvg('circle', {
                        r: seleccionado ? 8 : 6,
                        fill: colorConfianza(foco.confianza),
                        stroke: 'white',
                        'stroke-width': 2,
                    }),
                );
                marcador.addEventListener('click', () =>
                    alSeleccionar(foco.identificador),
                );
                marcador.addEventListener('keydown', (evento) => {
                    if (evento.key === 'Enter' || evento.key === ' ') {
                        evento.preventDefault();
                        alSeleccionar(foco.identificador);
                    }
                });
                capaFocos.append(marcador);
            });
        },
    };
}
