# 1. Alcance y estado real de la demo

**Fecha de documentación:** 8 de octubre de 2026  
**Fuente de verdad:** `README.md`, código del módulo Incendios, `docs/REGISTRO_CAMBIOS.md`, `docs/RESULTADOS_PRUEBAS.md`  
**Estado de implementación Git/GitHub:** Pendiente de evidencia (no acreditado por el código local)

## 1.1 Qué es la característica demostrada

GeoCruz demuestra, en una aplicación web, la consulta y visualización de **focos de calor** en el **departamento de Santa Cruz** (Bolivia), no solo en Santa Cruz de la Sierra.

Stack confirmado en el repositorio local:

- Laravel 12 y Blade (sin Vite ni compilación de JavaScript para la demo)
- Módulo propio `web/app/Modulos/Incendios/`
- API REST JSON consumible por la web y, en el futuro, por la app móvil
- Sin base de datos obligatoria para esta demo
- Sin autenticación

Responsabilidades del equipo (según README):

| Integrante | Rol |
|------------|-----|
| Josué Padilla | Desarrollo de la web |
| Jaziel Díaz | Desarrollo de la app móvil y revisión de la web |
| Codex | Programación de esta demo (autorizada localmente) |

La carpeta `movil/` solo reserva el trabajo de Jaziel; **todavía no contiene una aplicación móvil**.

## 1.2 Funcionalidad disponible hoy

En la interfaz web el usuario puede:

1. Abrir la pantalla de focos de calor.
2. Elegir período móvil: **24 horas**, **72 horas** o **7 días**.
3. Elegir fuente: **Demostración** (simulada) o **NASA FIRMS** (requiere clave).
4. Filtrar por **confianza del sensor** (todas / alta / nominal / baja).
5. Ver mapa con marcadores, listado ordenado por fecha y detalle del foco seleccionado.
6. Ver resumen de focos visibles (total, confianza alta, potencia máxima FRP).
7. Actualizar la consulta.
8. Usar la vista en pantalla pequeña (web adaptable; no es la app móvil).

Datos mostrados por foco (cuando existen):

- identificador;
- latitud y longitud;
- fecha y hora de detección;
- satélite e instrumento;
- confianza del sensor;
- potencia radiativa (FRP en MW, o valor nulo).

## 1.3 Datos simulados frente a integraciones reales

| Elemento | Estado real | Cómo documentarlo |
|----------|-------------|-------------------|
| Fuente **Demostración** | Implementada. Genera focos inventados en `DatosDemostracion.php`. | Datos **simulados**. No son detecciones NASA. |
| Aviso en pantalla | Visible en modo demo (“SIMULADO”). | Debe aparecer en capturas de evidencia local. |
| Mapa local | Implementado (`mapaDemostracion.js`) con límite GeoJSON simplificado. | Funciona sin Google ni red externa. |
| Límite departamental | GeoJSON real simplificado en `compartido/santa-cruz.geojson`. | Geometría académica, no catastro oficial de alta precisión. |
| NASA FIRMS | Código preparado (`ServicioNasaFirms`, normalización, caché 5 min, filtro territorial). | Requiere `NASA_FIRMS_CLAVE`. Sin clave → 503. **Consulta real: Pendiente de evidencia.** |
| Google Maps | Código preparado (`mapaGoogle.js`). | Requiere `GOOGLE_MAPS_CLAVE`. Sin clave o con falla → mapa de demostración. **Uso real: Pendiente de evidencia.** |
| Pruebas automatizadas | 11 pruebas Feature con `Http::fake`. | Verifican flujo y errores con respuestas **controladas**, no una conexión externa real. |
| Capturas locales | `docs/evidencias/demo-escritorio.png` y `demo-pantalla-pequena.png`. | Prueban UI con datos simulados. |

Regla de comportamiento confirmada en código: si falla NASA, se muestra error y **no** se sustituyen los datos por simulados.

## 1.4 Fuera de alcance de esta demo

No implementado (y no debe presentarse como hecho):

- autenticación de usuarios;
- persistencia histórica en PostgreSQL/PostGIS;
- alertas personalizadas;
- módulos de sequías, inundaciones, deforestación o calidad del aire;
- reportes ciudadanos;
- aplicación móvil funcional;
- índice de riesgo por municipio;
- superposición avanzada de capas o deck.gl.

## 1.5 Limitaciones académicas que deben decirse en defensa oral

1. Un foco térmico **no confirma** un incendio.
2. La confianza es del **sensor**, no mide gravedad.
3. El modo Demostración sirve para probar el flujo local.
4. Las pruebas con respuestas NASA falsas no equivalen a integración externa verificada.
5. Documentar después del desarrollo **no** sustituye planificación previa aprobada.
6. El código local **no acredita** issues, ramas, commits, PR, revisión ni merge.

## 1.6 Pendientes para cerrar la entrega

| Ítem | Estado |
|------|--------|
| Claves NASA y Google autorizadas + prueba real | Pendiente de evidencia |
| Revisión/aprobación de Jaziel con evidencia propia | Pendiente de evidencia |
| PERT / CPM / Planner aprobados | Pendiente de evidencia |
| Issue, rama, commits, PR y merge manuales | Pendiente de evidencia |
| Capturas de Git/GitHub y enlace al repositorio | Pendiente de evidencia |
| PDF de evidencias armado con capturas reales | Pendiente de evidencia |

**Pedido al equipo:** entregar capturas y enlaces reales (issue, rama, commits, PR, aprobación de Jaziel, merge a `develop`, repositorio) para completar las secciones marcadas como pendientes.
