# 3. Caso de uso — Visualizar focos de calor

**Identificador:** CU-01  
**Nombre:** Visualizar focos de calor  
**Estado:** borrador alineado a la implementación local  
**Historias relacionadas (borrador):** HU-01, HU-02, HU-03, HU-04, HU-05, HU-06

## 3.1 Actor

| Actor | Descripción |
|-------|-------------|
| Usuario | Persona que abre la demo web sin iniciar sesión |

Sistema secundario (externo, cuando se usa fuente NASA):

| Sistema | Rol |
|---------|-----|
| NASA FIRMS | Proveedor de detecciones térmicas (CSV) |
| Google Maps JavaScript API | Mapa base opcional (si hay clave) |

## 3.2 Precondiciones

1. La aplicación web está disponible (por ejemplo, servidor local en `http://127.0.0.1:8123`).
2. El límite geográfico `compartido/santa-cruz.geojson` es accesible para el backend.
3. El usuario puede abrir un navegador moderno.

Precondiciones opcionales según el escenario:

- Para NASA real: `NASA_FIRMS_CLAVE` configurada en `web/.env` (**Pendiente de evidencia** de prueba real).
- Para Google Maps: `GOOGLE_MAPS_CLAVE` válida (**Pendiente de evidencia** de uso real).

## 3.3 Postcondiciones (flujo principal exitoso)

1. El usuario ve un mapa con el límite departamental.
2. Ve marcadores y/o listado de focos del período y fuente elegidos.
3. Puede seleccionar un foco y ver su detalle.
4. El resumen refleja los focos visibles tras el filtro de confianza.

## 3.4 Flujo principal

Escenario de referencia: fuente **Demostración** (datos simulados) y mapa local o Google, según configuración.

1. El usuario ingresa a la pantalla de focos de calor (`/` o `/incendios`).
2. El sistema carga la página Blade y los assets del módulo.
3. El frontend solicita el límite departamental a `GET /api/incendios/limite`.
4. El sistema inicializa el mapa:
   - con Google Maps si hay clave;
   - o con el mapa de demostración si no hay clave o Google falla.
5. El frontend solicita focos a `GET /api/incendios?dias=1&fuente=demo` (valores por defecto).
6. El backend valida parámetros, obtiene focos simulados, filtra por ventana móvil y territorio, y responde JSON.
7. El frontend coloca marcadores, llena el listado y actualiza el resumen.
8. El usuario selecciona un marcador o una fila.
9. El sistema muestra el detalle del foco (fecha/hora, coordenadas, satélite, instrumento, confianza, FRP).
10. El usuario puede cambiar período, confianza o fuente; el sistema actualiza resultados según corresponda.

## 3.5 Flujos alternativos

### A1 — No existen focos para los filtros

**Disparador:** la consulta es válida y el conjunto filtrado queda vacío (período/fuente/confianza).

1. El backend responde 200 con `focos: []` (o el frontend filtra todos en cliente).
2. El sistema muestra estado vacío: no hay detecciones para estos filtros.
3. El resumen refleja cero focos visibles.
4. El flujo termina sin error de servidor.

### A2 — Clave NASA inválida o ausente

**Disparador:** el usuario elige fuente `nasa` y no hay clave, o la clave no permite una respuesta CSV válida.

1. Sin clave: el backend responde **503** con mensaje que indica configurar `NASA_FIRMS_CLAVE`.
2. Con respuesta inválida de FIRMS: el backend responde **503** con mensaje de CSV/servicio no válido.
3. El frontend muestra el mensaje de error.
4. El sistema **no** sustituye la respuesta por datos simulados.
5. El usuario puede volver a fuente Demostración para continuar la prueba local.

**Evidencia de clave inválida en entorno real:** Pendiente de evidencia.

### A3 — Falla de NASA FIRMS (conexión o servicio)

**Disparador:** error de red, timeout o respuesta HTTP no exitosa al consultar FIRMS.

1. El backend captura el fallo y responde **503** con mensaje comprensible.
2. El frontend muestra el error.
3. No se inventan focos de reemplazo.
4. El listado/mapa quedan sin consulta exitosa hasta un nuevo intento.

**Nota:** las pruebas automatizadas cubren este caso con respuestas **controladas** (`Http::fake`), no con una caída real de NASA.  
**Evidencia de falla real externa:** Pendiente de evidencia.

### A4 — Falla del mapa / Google no disponible

**Disparador:** no hay `GOOGLE_MAPS_CLAVE`, la API de Maps falla, o el script de Google no carga.

1. El frontend usa el mapa de demostración.
2. Se indica en pantalla que se utiliza el mapa de demostración / Google no disponible.
3. La lista, el resumen y el detalle siguen funcionando con los datos de la API.
4. Si además falla la carga del límite GeoJSON, el contenedor del mapa muestra un mensaje de error; la consulta de focos puede seguir siendo independiente.

### A5 — Parámetros de consulta inválidos

**Disparador:** `dias` distinto de 1, 3 o 7; `fuente` distinta de `demo` o `nasa`; o parámetros enviados como arreglos.

1. El backend responde **422** con mensaje de consulta inválida.
2. El frontend muestra el error.
3. No se renderizan focos de esa respuesta.

### A6 — Error de red del navegador hacia la API propia

**Disparador:** el frontend no puede completar `fetch` hacia `/api/incendios`.

1. El frontend captura el error.
2. Muestra que la consulta no pudo completarse.
3. No actualiza focos con una respuesta parcial anterior de otra consulta más reciente (control de carrera por número de consulta).

## 3.6 Diagrama conceptual (texto)

```
Usuario
  → Pantalla Incendios (Blade + JS)
      → GET /api/incendios/limite
      → Mapa (Google o demostración)
      → GET /api/incendios?dias=&fuente=
          → ControladorIncendios (validación)
          → ServicioIncendios
              → DatosDemostracion  ó  ServicioNasaFirms → NASA FIRMS
              → Filtro temporal + GeografiaSantaCruz
          ← JSON (focos, resumen, consulta)
      → Marcadores + listado + detalle + resumen
```

## 3.7 Reglas de negocio documentables

1. Períodos aceptados: 1, 3 y 7 días (ventanas móviles).
2. Fuentes aceptadas: `demo` y `nasa`.
3. Filtrado territorial: solo coordenadas dentro del polígono de Santa Cruz.
4. Confianza del sensor ≠ gravedad.
5. Foco térmico ≠ incendio confirmado.
6. `consulta.simulados = true` cuando la fuente es demo.
