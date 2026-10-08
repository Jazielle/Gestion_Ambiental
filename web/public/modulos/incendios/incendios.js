import { crearMapaDemostracion } from './mapaDemostracion.js';
import { crearMapaGoogle } from './mapaGoogle.js';
import {
    formatearFecha,
    formatearPotencia,
    nombreConfianza,
} from './formato.js';

// 1. Elementos de la pantalla: aquí se localizan los controles y las salidas.
const buscar = (identificador) => document.getElementById(identificador);
const elementos = {
    actualizar: buscar('boton-actualizar'),
    periodos: [...document.querySelectorAll('[data-dias]')],
    confianza: buscar('filtro-confianza'),
    fuente: buscar('filtro-fuente'),
    mapa: buscar('contenedor-mapa'),
    lista: buscar('lista-focos'),
    detalle: buscar('detalle-foco'),
    error: buscar('mensaje-error'),
    estado: buscar('estado-consulta'),
};
const configuracion = JSON.parse(buscar('configuracion-incendios').textContent);

// 2. Estado de la funcionalidad: datos, filtros y selección permanecen juntos.
const estado = {
    dias: 1,
    fuente: 'demo',
    confianza: 'todas',
    focos: [],
    identificadorSeleccionado: null,
    mapa: null,
    limite: null,
    numeroConsulta: 0,
    cancelacion: null,
    consulta: null,
};

function obtenerFocosVisibles() {
    return estado.focos.filter(
        (foco) =>
            estado.confianza === 'todas' || foco.confianza === estado.confianza,
    );
}

function seleccionarFoco(identificador) {
    estado.identificadorSeleccionado = identificador;
    actualizarResultados();
}

// 3. Presentación: usamos textContent para evitar interpretar datos externos como HTML.
function actualizarDetalle(foco) {
    elementos.detalle.hidden = !foco;
    if (!foco) return;
    buscar('detalle-identificador').textContent = foco.identificador;
    buscar('detalle-confianza').textContent = nombreConfianza(foco.confianza);
    buscar('detalle-confianza').className =
        `insignia-confianza ${foco.confianza}`;
    buscar('detalle-fecha').textContent = formatearFecha(foco.fechaDeteccion);
    buscar('detalle-coordenadas').textContent =
        `${foco.latitud.toFixed(4)}, ${foco.longitud.toFixed(4)}`;
    buscar('detalle-satelite').textContent = foco.satelite;
    buscar('detalle-instrumento').textContent = foco.instrumento;
    buscar('detalle-potencia').textContent = formatearPotencia(
        foco.potenciaRadiativa,
    );
}

function actualizarLista(focos) {
    elementos.lista.replaceChildren();
    if (!focos.length) {
        const mensaje = document.createElement('p');
        mensaje.className = 'estado-vacio';
        mensaje.textContent = estado.consulta
            ? 'No hay detecciones para estos filtros.'
            : 'Todavía no hay una consulta disponible.';
        elementos.lista.append(mensaje);
        return;
    }

    focos.forEach((foco) => {
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = `fila-foco ${foco.identificador === estado.identificadorSeleccionado ? 'seleccionada' : ''}`;
        boton.setAttribute(
            'aria-pressed',
            String(foco.identificador === estado.identificadorSeleccionado),
        );
        const punto = document.createElement('i');
        punto.className = `punto ${foco.confianza}`;
        const contenido = document.createElement('span');
        const titulo = document.createElement('strong');
        titulo.textContent = `${foco.latitud.toFixed(3)}, ${foco.longitud.toFixed(3)}`;
        const fecha = document.createElement('small');
        fecha.textContent = formatearFecha(foco.fechaDeteccion);
        contenido.append(titulo, fecha);
        const confianza = document.createElement('span');
        confianza.className = `insignia-confianza ${foco.confianza}`;
        confianza.textContent = nombreConfianza(foco.confianza);
        boton.append(punto, contenido, confianza);
        boton.addEventListener('click', () =>
            seleccionarFoco(foco.identificador),
        );
        elementos.lista.append(boton);
    });
}

function actualizarResultados() {
    const focos = obtenerFocosVisibles();
    const seleccionado = focos.find(
        (foco) => foco.identificador === estado.identificadorSeleccionado,
    );
    if (!seleccionado) estado.identificadorSeleccionado = null;
    const potencias = focos
        .map((foco) => foco.potenciaRadiativa)
        .filter((potencia) => potencia !== null);
    buscar('resumen-total').textContent = estado.consulta ? focos.length : '—';
    buscar('resumen-alta').textContent = estado.consulta
        ? focos.filter((foco) => foco.confianza === 'alta').length
        : '—';
    buscar('resumen-potencia').textContent = estado.consulta
        ? potencias.length
            ? formatearPotencia(Math.max(...potencias))
            : '—'
        : '—';
    buscar('cantidad-lista').textContent = estado.consulta ? focos.length : '—';
    buscar('cantidad-mapa').textContent = estado.consulta
        ? `${focos.length} focos visibles`
        : 'Sin consulta';
    actualizarLista(focos);
    actualizarDetalle(seleccionado);
    estado.mapa?.mostrarFocos(focos, estado.identificadorSeleccionado);
}

function actualizarAvisoFuente() {
    const esDemostracion = estado.fuente === 'demo';
    buscar('titulo-fuente').textContent = esDemostracion
        ? 'Modo demostración'
        : 'Consulta a NASA FIRMS';
    buscar('descripcion-fuente').textContent = esDemostracion
        ? 'Datos simulados para probar la aplicación. No representan incendios reales.'
        : 'Detecciones térmicas de VIIRS Suomi NPP. La disponibilidad depende del paso del satélite.';
    buscar('insignia-fuente').textContent = esDemostracion
        ? 'SIMULADO'
        : 'NASA FIRMS';
    buscar('aviso-fuente').classList.toggle('fuente-real', !esDemostracion);
}

// 4. Consultas: una respuesta anterior nunca reemplaza una selección más reciente.
async function consultarDatos() {
    const numeroConsulta = ++estado.numeroConsulta;
    estado.cancelacion?.abort();
    estado.cancelacion = new AbortController();
    elementos.actualizar.disabled = true;
    elementos.mapa.setAttribute('aria-busy', 'true');
    elementos.error.hidden = true;
    elementos.estado.textContent = 'Consultando focos de calor…';
    buscar('fecha-actualizacion').textContent = 'Consultando…';
    estado.focos = [];
    estado.consulta = null;
    estado.identificadorSeleccionado = null;
    actualizarAvisoFuente();
    actualizarResultados();

    try {
        const respuesta = await fetch(
            `/api/incendios?dias=${estado.dias}&fuente=${estado.fuente}`,
            {
                headers: { Accept: 'application/json' },
                signal: estado.cancelacion.signal,
            },
        );
        const contenido = await respuesta.json();
        if (!respuesta.ok)
            throw new Error(
                contenido.mensaje ?? 'No se pudo completar la consulta.',
            );
        if (numeroConsulta !== estado.numeroConsulta) return;
        estado.focos = contenido.focos;
        estado.consulta = contenido.consulta;
        actualizarResultados();
        buscar('fecha-actualizacion').textContent =
            `Consultado: ${formatearFecha(contenido.consulta.fechaConsulta)}`;
        elementos.estado.textContent = `${obtenerFocosVisibles().length} detecciones disponibles.`;
    } catch (error) {
        if (
            error.name === 'AbortError' ||
            numeroConsulta !== estado.numeroConsulta
        )
            return;
        elementos.error.textContent = error.message;
        elementos.error.hidden = false;
        buscar('fecha-actualizacion').textContent = 'Consulta no disponible';
        elementos.estado.textContent = 'La consulta no pudo completarse.';
    } finally {
        if (numeroConsulta === estado.numeroConsulta) {
            elementos.actualizar.disabled = false;
            elementos.mapa.setAttribute('aria-busy', 'false');
        }
    }
}

async function prepararMapa() {
    try {
        const respuesta = await fetch('/api/incendios/limite', {
            headers: { Accept: 'application/json' },
        });
        if (!respuesta.ok)
            throw new Error('No se pudo cargar el límite departamental.');
        estado.limite = await respuesta.json();
        function usarMapaDemostracion(error) {
            estado.mapa = crearMapaDemostracion(
                elementos.mapa,
                estado.limite,
                seleccionarFoco,
            );
            buscar('nombre-mapa').textContent =
                'Mapa de demostración · Google Maps no disponible';
            elementos.estado.textContent = error.message;
            actualizarResultados();
        }
        if (configuracion.claveGoogle) {
            try {
                estado.mapa = await crearMapaGoogle(
                    elementos.mapa,
                    estado.limite,
                    configuracion,
                    seleccionarFoco,
                    usarMapaDemostracion,
                );
                buscar('nombre-mapa').textContent = 'Google Maps';
            } catch (error) {
                usarMapaDemostracion(error);
            }
        } else {
            estado.mapa = crearMapaDemostracion(
                elementos.mapa,
                estado.limite,
                seleccionarFoco,
            );
        }
        actualizarResultados();
    } catch (error) {
        const mensaje = document.createElement('p');
        mensaje.className = 'estado-vacio';
        mensaje.textContent = error.message;
        elementos.mapa.replaceChildren(mensaje);
    }
}

// 5. Eventos: cada control afecta únicamente al estado de incendios.
elementos.periodos.forEach((boton) =>
    boton.addEventListener('click', () => {
        estado.dias = Number(boton.dataset.dias);
        elementos.periodos.forEach((periodo) =>
            periodo.setAttribute('aria-pressed', String(periodo === boton)),
        );
        consultarDatos();
    }),
);
elementos.confianza.addEventListener('change', () => {
    estado.confianza = elementos.confianza.value;
    actualizarResultados();
    elementos.estado.textContent = `${obtenerFocosVisibles().length} focos visibles con el filtro seleccionado.`;
});
elementos.fuente.addEventListener('change', () => {
    estado.fuente = elementos.fuente.value;
    consultarDatos();
});
elementos.actualizar.addEventListener('click', consultarDatos);

// El mapa y los datos se cargan por separado: una falla del mapa no oculta la lista.
prepararMapa();
consultarDatos();
