# 5. Guía de prueba manual

**Propósito:** reproducir y registrar la verificación humana de la demo web.  
**Alcance por defecto:** fuente **Demostración** (datos simulados) y mapa local o Google según `.env`.  
**No confundir** una prueba con datos simulados o `Http::fake` con una conexión externa verificada.

## 5.1 Preparación

1. Abrir PowerShell en la raíz del proyecto.
2. Ejecutar:

```powershell
powershell -ExecutionPolicy Bypass -File .\herramientas\iniciar-demo.ps1
```

3. Visitar `http://127.0.0.1:8123` (u otro puerto si se indicó `-Puerto`).
4. Mantener la terminal abierta.

Opcional automatizado (no sustituye la revisión de Jaziel):

```powershell
powershell -ExecutionPolicy Bypass -File .\herramientas\probar-demo.ps1
```

## 5.2 Matriz de casos manuales

Completar las columnas **Fecha**, **Autor** y **Resultado observado** solo con datos reales aportados por el equipo. Mientras no existan, dejar **Pendiente de evidencia**.

| ID | Paso | Resultado esperado | Fecha | Autor | Resultado observado |
|----|------|--------------------|-------|-------|---------------------|
| PM-01 | Iniciar app y abrir la pantalla | Carga la UI de focos de calor; no se expone clave NASA en HTML | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-02 | Verificar aviso de fuente Demostración | Texto/insignia de datos simulados visible | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-03 | Consultar 24 horas (`dias=1`) | Aparecen focos simulados del período; resumen coherente | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-04 | Consultar 72 horas (`dias=3`) | Se actualiza la consulta; conjunto ≥ 24 h en demo | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-05 | Consultar 7 días (`dias=7`) | Más focos que en 24 h en fuente demo (p. ej. 16 vs 7 en pruebas Codex) | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-06 | Filtrar confianza alta / nominal / baja | Mapa, lista y resumen se reducen al filtro | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-07 | Seleccionar un marcador del mapa | Se muestra detalle y la fila correspondiente queda marcada | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-08 | Seleccionar una fila del listado | Se muestra el mismo detalle; marcador coherente | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-09 | Pulsar Actualizar | Nueva consulta; fecha de actualización cambia | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-10 | Reducir ventana (~390 px de ancho) | Sin desbordamiento horizontal grave; controles usables | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-11 | Elegir fuente NASA **sin** clave | Error visible (503); **no** aparecen focos simulados de reemplazo | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-12 | Elegir fuente NASA **con** clave válida | Detecciones reales normalizadas; `simulados=false` | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-13 | Probar Google Maps con clave válida | Mapa Google activo; si falla, cae a mapa demo con aviso | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |
| PM-14 | Escenario sin focos (filtros extremos o respuesta vacía controlada) | Mensaje de consulta vacía, sin fallo de UI | Pendiente de evidencia | Pendiente de evidencia | Pendiente de evidencia |

## 5.3 Detalle de ejecución recomendado

### A. Inicio

1. Ejecutar `iniciar-demo.ps1`.
2. Abrir la URL local.
3. Comprobar título “Focos de calor” y territorio Santa Cruz.
4. Confirmar insignia **SIMULADO** con fuente Demostración.

### B. Períodos

1. Pulsar **24 horas** y anotar total visible.
2. Pulsar **72 horas** y anotar total.
3. Pulsar **7 días** y anotar total.
4. Volver a 24 horas.

### C. Confianza

1. Con 7 días cargados, elegir confianza **Alta**.
2. Verificar que lista y marcadores disminuyen.
3. Probar Nominal y Baja.
4. Volver a **Todas**.

### D. Selección

1. Clic en un marcador → revisar detalle (fecha, coordenadas, satélite, instrumento, confianza, FRP).
2. Clic en otra fila del listado → el detalle cambia.
3. Confirmar que la confianza no se describe como gravedad.

### E. Actualizar y errores

1. Pulsar **Actualizar**.
2. Cambiar fuente a **NASA FIRMS** sin clave → debe fallar con mensaje claro.
3. Volver a **Demostración**.

### F. Pantalla pequeña

1. Redimensionar el navegador o usar herramientas de dispositivo.
2. Verificar filtros, mapa y lista usables.
3. Esta prueba es **web adaptable**, no la app móvil de Jaziel.

## 5.4 Evidencias visuales ya disponibles (locales)

| Archivo | Qué demuestra | Qué no demuestra |
|---------|---------------|------------------|
| `docs/evidencias/demo-escritorio.png` | UI escritorio con simulación | NASA/Google reales, GitHub |
| `docs/evidencias/demo-pantalla-pequena.png` | UI estrecha web | App móvil nativa |

## 5.5 Comprobaciones ya registradas por Codex (no son aprobación de Jaziel)

Ver [RESULTADOS_PRUEBAS.md](RESULTADOS_PRUEBAS.md):

- Fecha: 8 de octubre de 2026.
- 11 pruebas automatizadas / 59 aserciones (NASA falseada).
- Playwright local en escritorio y 390×844.
- Autor de esas comprobaciones: Codex en entorno de Josué.

**Revisión de Jaziel:** Pendiente de evidencia.

## 5.6 Plantilla para pegar un resultado real

```
ID: PM-0X
Fecha: AAAA-MM-DD
Autor: Nombre
Entorno: navegador / SO / URL
Fuente: demo | nasa
Mapa: demostración | Google
Resultado: OK / FALLO
Observaciones:
Captura:
```
