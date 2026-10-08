# 2. Historias de usuario (borrador)

**Estado:** borradores propuestos a partir de la demo implementada.  
**No son aprobaciones formales del docente ni del equipo.**  
Si existe una versión aprobada distinta, esa versión prevalece; estos textos quedan como propuesta de alineación.

**Alcance:** módulo web de focos de calor · departamento de Santa Cruz  
**Actor principal:** usuario visitante (sin autenticación)

---

## HU-01 — Visualizar focos de calor en el mapa

**Como** usuario,  
**quiero** visualizar en un mapa los focos de calor del departamento de Santa Cruz,  
**para** identificar zonas con posibles incendios o anomalías térmicas.

### Criterios de aceptación (propuestos)

- [ ] Al abrir la pantalla se carga un mapa (Google Maps si hay clave válida; si no, mapa de demostración).
- [ ] Se solicitan focos al backend y se muestran como marcadores dentro del territorio.
- [ ] Existe un aviso claro cuando la fuente es **Demostración** (datos simulados).
- [ ] La interfaz no presenta un foco como “incendio confirmado”.
- [ ] Si no hay focos para los filtros, se muestra un estado vacío comprensible.

**Relación con implementación:** mapa + listado + API `GET /api/incendios`.  
**Evidencia Git:** Pendiente de evidencia.

---

## HU-02 — Seleccionar período de consulta

**Como** usuario,  
**quiero** elegir el período de consulta,  
**para** ver focos de las últimas 24 horas, 72 horas o 7 días.

### Criterios de aceptación (propuestos)

- [ ] Hay controles para 24 horas (`dias=1`), 72 horas (`dias=3`) y 7 días (`dias=7`).
- [ ] Al cambiar el período se vuelve a consultar la API.
- [ ] El período de 7 días muestra al menos tantos focos como el de 24 horas en la fuente Demostración (comportamiento observado en pruebas locales).
- [ ] Un valor de período inválido es rechazado por la API (422).

**Nota:** la demo implementada incluye **72 horas**, además de 24 horas y 7 días.

**Evidencia Git:** Pendiente de evidencia.

---

## HU-03 — Consultar el detalle de un foco

**Como** usuario,  
**quiero** seleccionar un foco en el mapa o en el listado,  
**para** conocer fecha/hora, coordenadas, satélite, instrumento, confianza y potencia radiativa.

### Criterios de aceptación (propuestos)

- [ ] Al seleccionar un marcador o una fila se muestra el panel de detalle.
- [ ] El detalle incluye identificador, fecha/hora, coordenadas, satélite, instrumento, confianza y FRP (o indicador de no disponible).
- [ ] La confianza se presenta como atributo del sensor (alta / nominal / baja), no como gravedad.
- [ ] La selección desde mapa y desde listado permanece coherente.

**Evidencia Git:** Pendiente de evidencia.

---

## HU-04 — Ver resumen de focos visibles

**Como** usuario,  
**quiero** ver un resumen de los focos visibles,  
**para** conocer rápidamente el estado de la consulta.

### Criterios de aceptación (propuestos)

- [ ] Se muestra el total de focos visibles según período, fuente y filtro de confianza.
- [ ] Se muestra la cantidad con confianza alta.
- [ ] Se muestra la potencia radiativa máxima entre los focos visibles (cuando exista).
- [ ] El resumen se actualiza al cambiar filtros sin confundir datos simulados con NASA.

**Evidencia Git:** Pendiente de evidencia.

---

## HU-05 — Filtrar por confianza del sensor (propuesta adicional)

**Como** usuario,  
**quiero** filtrar los focos por nivel de confianza del sensor,  
**para** concentrarme en detecciones de mayor o menor confianza.

### Criterios de aceptación (propuestos)

- [ ] El filtro ofrece: todas, alta, nominal y baja.
- [ ] El filtro actúa sobre los focos ya consultados (lado cliente) y actualiza mapa, lista y resumen.
- [ ] Queda explícito que la confianza no mide gravedad del evento.

**Evidencia Git:** Pendiente de evidencia.

---

## HU-06 — Elegir fuente de datos (propuesta adicional)

**Como** usuario,  
**quiero** elegir entre datos de demostración y NASA FIRMS,  
**para** probar el flujo local o consultar detecciones térmicas reales cuando haya clave.

### Criterios de aceptación (propuestos)

- [ ] Fuente `demo` devuelve focos simulados e identifica la consulta como simulada.
- [ ] Fuente `nasa` sin clave responde error claro (503) y no rellena con datos simulados.
- [ ] Fuente `nasa` con clave válida ( Pendiente de evidencia ) consulta VIIRS Suomi NPP y normaliza campos.
- [ ] La UI distingue visualmente modo simulado y modo NASA.

---

## Matriz rápida HU ↔ pantalla

| Historia | Mapa | Período | Detalle | Resumen | Confianza | Fuente |
|----------|:----:|:-------:|:-------:|:-------:|:---------:|:------:|
| HU-01 | ● | | | | | |
| HU-02 | | ● | | | | |
| HU-03 | ● | | ● | | | |
| HU-04 | | | | ● | ● | |
| HU-05 | ● | | | ● | ● | |
| HU-06 | | | | | | ● |
