import { colorConfianza } from './formato.js';

let promesaGoogle;

function cargarGoogle(clave) {
    if (promesaGoogle) return promesaGoogle;
    promesaGoogle = new Promise((resolver, rechazar) => {
        const temporizador = setTimeout(
            () =>
                rechazar(
                    new Error(
                        'Google Maps no respondió. Se mostrará el mapa de demostración.',
                    ),
                ),
            15000,
        );
        window.geocruzMapaListo = () => {
            clearTimeout(temporizador);
            resolver(window.google.maps);
        };
        window.gm_authFailure = () => {
            clearTimeout(temporizador);
            rechazar(
                new Error(
                    'Google Maps rechazó la clave. Se mostrará el mapa de demostración.',
                ),
            );
        };
        const archivo = document.createElement('script');
        archivo.src = `https://maps.googleapis.com/maps/api/js?key=${encodeURIComponent(clave)}&loading=async&libraries=marker&callback=geocruzMapaListo&language=es&region=BO&v=weekly`;
        archivo.async = true;
        archivo.onerror = () => {
            clearTimeout(temporizador);
            rechazar(
                new Error(
                    'No se pudo cargar Google Maps. Se mostrará el mapa de demostración.',
                ),
            );
        };
        document.head.append(archivo);
    });
    return promesaGoogle;
}

export async function crearMapaGoogle(
    contenedor,
    limite,
    configuracion,
    alSeleccionar,
    alFallar,
) {
    const bibliotecaMapas = await cargarGoogle(configuracion.claveGoogle);
    contenedor.replaceChildren();
    const mapa = new bibliotecaMapas.Map(contenedor, {
        center: { lat: -17.5, lng: -61.4 },
        zoom: 6,
        mapId: configuracion.identificadorMapa,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: true,
    });
    mapa.data.addGeoJson(limite);
    mapa.data.setStyle({
        fillColor: '#087f82',
        fillOpacity: 0.08,
        strokeColor: '#087f82',
        strokeWeight: 2,
    });
    const limitesVista = new bibliotecaMapas.LatLngBounds();
    mapa.data.forEach((elemento) =>
        elemento
            .getGeometry()
            .forEachLatLng((coordenada) => limitesVista.extend(coordenada)),
    );
    mapa.fitBounds(limitesVista);
    // Google puede notificar un error de autorización después de ejecutar su callback inicial.
    window.gm_authFailure = () =>
        alFallar(
            new Error(
                'Google Maps rechazó la clave. Se mostrará el mapa de demostración.',
            ),
        );
    let marcadores = [];

    return {
        mostrarFocos(focos, identificadorSeleccionado) {
            marcadores.forEach((marcador) => {
                marcador.map = null;
            });
            marcadores = focos.map((foco) => {
                const pin = new bibliotecaMapas.marker.PinElement({
                    background: colorConfianza(foco.confianza),
                    borderColor: 'white',
                    glyphColor: 'white',
                    scale:
                        foco.identificador === identificadorSeleccionado
                            ? 1.25
                            : 0.85,
                });
                const marcador =
                    new bibliotecaMapas.marker.AdvancedMarkerElement({
                        map: mapa,
                        position: { lat: foco.latitud, lng: foco.longitud },
                        title: `Foco ${foco.identificador}`,
                        content: pin.element,
                    });
                marcador.addListener('click', () =>
                    alSeleccionar(foco.identificador),
                );
                return marcador;
            });
        },
    };
}
