# 4. Recorrido del código y contrato de API

Documento orientado a web y a la futura app móvil. Los nombres propios del módulo están en español; las interfaces obligatorias de Laravel, PHP, JavaScript, NASA y Google conservan su nombre original.

Referencia complementaria: [MAPA_DEL_CODIGO.md](MAPA_DEL_CODIGO.md).

## 4.1 Recorrido end-to-end

```
Filtro en pantalla
  → incendios.js (estado + fetch)
  → rutasApi.php
  → ControladorIncendios (validación)
  → ServicioIncendios (coordinación)
      → DatosDemostracion          si fuente=demo   (SIMULADO)
      → ServicioNasaFirms          si fuente=nasa   (requiere clave)
          → NormalizadorFirms      CSV → campos en español
      → filtro de ventana móvil
      → GeografiaSantaCruz         pertenencia al departamento
  ← JSON { focos, resumen, consulta }
  → mapa (Google o demostración) + listado + resumen + detalle
```

## 4.2 Dónde tocar cada responsabilidad

### Servidor (`web/app/Modulos/Incendios/`)

| Archivo | Responsabilidad | Cuándo editarlo |
|---------|-----------------|-----------------|
| `rutasWeb.php` | URLs de la pantalla | Cambiar rutas HTML |
| `rutasApi.php` | URLs JSON | Cambiar rutas de API |
| `ControladorIncendios.php` | Validar query y devolver JSON/vista | Reglas de entrada o códigos HTTP |
| `ServicioIncendios.php` | Orquestar fuente, filtros y resumen | Lógica de período/territorio/resumen |
| `ServicioNasaFirms.php` | HTTP a FIRMS, bloques de fechas, caché | Integración NASA |
| `NormalizadorFirms.php` | CSV → foco en español; descartar inválidos/duplicados | Campos o validación de filas |
| `DatosDemostracion.php` | Escenarios inventados | Cambiar datos **simulados** |
| `GeografiaSantaCruz.php` | Límite GeoJSON, caja bbox, punto en polígono | Territorio |

Configuración: `web/config/incendios.php`  
Variables documentadas (sin secretos): `web/.env.example`

| Variable (ejemplo) | Uso |
|--------------------|-----|
| `NASA_FIRMS_CLAVE` | Clave servidor para FIRMS (vacía = demo sin NASA) |
| `GOOGLE_MAPS_CLAVE` | Clave navegador para Maps |
| `GOOGLE_MAPS_ID` | Identificador de mapa (`DEMO_MAP_ID` por defecto) |

**No documentar ni copiar valores de `web/.env`.**

### Pantalla (Blade)

| Archivo | Responsabilidad |
|---------|-----------------|
| `web/resources/views/incendios/pagina.blade.php` | Estructura de la página |
| `.../componentes/filtros.blade.php` | Período, confianza, fuente |
| `.../componentes/resumen.blade.php` | Indicadores |
| `.../componentes/detalle.blade.php` | Panel de detalle |

### Interacción (`web/public/modulos/incendios/`)

| Archivo | Secciones / rol |
|---------|-----------------|
| `incendios.js` | 1 elementos · 2 estado · 3 presentación · 4 consultas · 5 eventos |
| `mapaDemostracion.js` | Mapa local sin Google |
| `mapaGoogle.js` | Google Maps + marcadores avanzados |
| `formato.js` | Fechas, potencia, nombres de confianza |
| `incendios.css` | Apariencia y pantalla pequeña |

### Móvil

| Ubicación | Estado |
|-----------|--------|
| `movil/` | Reserva para Jaziel. **Sin app implementada.** |
| Consumo previsto | Misma API JSON descrita abajo. En dispositivo, `127.0.0.1` no apunta a la PC del servidor. |

## 4.3 Contrato de API

Base local típica: `http://127.0.0.1:8123`

### `GET /api/incendios`

Consulta focos de calor.

**Query**

| Parámetro | Obligatorio | Valores | Predeterminado |
|-----------|-------------|---------|----------------|
| `dias` | No | `1`, `3`, `7` | `1` |
| `fuente` | No | `demo`, `nasa` | `demo` |

**Respuesta 200**

```json
{
  "focos": [
    {
      "identificador": "demo-001",
      "latitud": -16.35,
      "longitud": -61.10,
      "fechaDeteccion": "2026-10-08T19:00:00+00:00",
      "satelite": "Suomi NPP (simulado)",
      "instrumento": "VIIRS",
      "confianza": "alta",
      "potenciaRadiativa": 36.2
    }
  ],
  "resumen": {
    "total": 7,
    "confianzaAlta": 3,
    "potenciaMaxima": 42.6
  },
  "consulta": {
    "fuente": "demo",
    "simulados": true,
    "dias": 1,
    "fechaConsulta": "2026-10-08T20:00:00+00:00",
    "fechaInicio": "2026-10-07T20:00:00+00:00",
    "descripcion": "Datos simulados para demostración"
  }
}
```

**Campos de cada foco**

| Campo | Tipo | Notas |
|-------|------|-------|
| `identificador` | string | `demo-###` o hash `firms-...` |
| `latitud` / `longitud` | number | WGS84 |
| `fechaDeteccion` | string | ISO 8601 con zona (UTC en origen) |
| `satelite` | string | En demo incluye “(simulado)” |
| `instrumento` | string | p. ej. VIIRS |
| `confianza` | string | `alta` \| `nominal` \| `baja` \| `desconocida` |
| `potenciaRadiativa` | number \| null | MW (FRP) |

**`consulta.simulados`:** `true` solo con fuente demo. Usar este campo en móvil/web para no presentar simulaciones como detecciones reales.

**Errores**

| HTTP | Cuándo | Cuerpo |
|------|--------|--------|
| 422 | Parámetros inválidos | `{ "mensaje": "..." }` |
| 503 | NASA sin clave, CSV inválido, falla de red/servicio | `{ "mensaje": "..." }` |

### `GET /api/incendios/limite`

Devuelve el GeoJSON del límite departamental usado por el mapa y el filtro territorial.

**Respuesta 200:** Feature GeoJSON (geometría Polygon/MultiPolygon según el archivo compartido).

## 4.4 Comportamientos importantes para documentar

1. FIRMS trabaja con días de calendario UTC; el backend consulta bloques de hasta 5 días y luego aplica la ventana móvil exacta.
2. Caché de consultas NASA: 5 minutos (`minutos_cache`).
3. Sensor configurado: `VIIRS_SNPP_NRT`.
4. La UI presenta horas en zona de Bolivia (UTC−4) mediante formateo en cliente.
5. El filtro de confianza visible actúa en el **cliente** sobre los focos ya recibidos.
6. El resumen de la API es del conjunto filtrado por tiempo/territorio; el resumen de pantalla puede reducirse además por confianza.

## 4.5 Qué no afirmar todavía

| Afirmación | Estado correcto |
|------------|-----------------|
| “La app móvil ya consume la API” | Propuesto / no implementado |
| “NASA está verificado en producción” | Pendiente de evidencia |
| “Google Maps está verificado con clave real” | Pendiente de evidencia |
| “Hay base PostGIS con históricos” | No implementado en esta demo |
