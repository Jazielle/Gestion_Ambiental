# 8. Trazabilidad y planificación

Este documento conecta la demo implementada con el orden exigido por el docente. Distingue **propuesto**, **implementado localmente**, **probado en local (simulado/controlado)** y **Pendiente de evidencia**.

## 8.1 Orden docente y estado

| Paso | Artefacto | Estado en este repositorio documental |
|------|-----------|----------------------------------------|
| 1 | PERT | Pendiente de evidencia (no hay versión aprobada en `docs/`) |
| 2 | CPM | Pendiente de evidencia |
| 3 | Planner | Pendiente de evidencia |
| 4 | Historias de Usuario | Borrador en `02_HISTORIAS_DE_USUARIO.md` (no aprobación formal) |
| 5 | Casos de Uso | Borrador en `03_CASO_DE_USO.md` |
| 6 | Issues | Borradores en `07_BORRADORES_ISSUE_Y_PR.md` — **no creados en GitHub** |
| 7 | Milestone | Borrador “Demo Incendios MVP” — **no creado en GitHub** |
| 8 | `main` | Política documentada — existencia/uso real: Pendiente de evidencia |
| 9 | `develop` | Política documentada — Pendiente de evidencia |
| 10 | `feature/*` | Nombres sugeridos — Pendiente de evidencia |
| 11 | Pull Request | Plantilla lista — Pendiente de evidencia |

Si Josué entrega PERT/CPM/Planner aprobados, deben guardarse en `docs/` y **prevalecer** sobre cualquier propuesta posterior. Los ajustes de Cursor se marcarán como propuestas, sin inventar fechas ni estimaciones.

## 8.2 Matriz de trazabilidad (demo incendios)

| HU (borrador) | CU | Issue borrador | Funcionalidad implementada | Prueba local | Evidencia GitHub |
|---------------|----|----------------|----------------------------|--------------|------------------|
| HU-01 Mapa de focos | CU-01 | Issue A / C | Mapa demo/Google + marcadores | Codex + capturas UI | Pendiente de evidencia |
| HU-02 Período | CU-01 | Issue B / C | `dias=1\|3\|7` | Feature + UI | Pendiente de evidencia |
| HU-03 Detalle | CU-01 | Issue C | Panel detalle + selección | UI Playwright | Pendiente de evidencia |
| HU-04 Resumen | CU-01 | Issue B / C | Resumen API + UI | Feature + UI | Pendiente de evidencia |
| HU-05 Confianza | CU-01 | Issue C | Filtro cliente | UI | Pendiente de evidencia |
| HU-06 Fuente | CU-01 A2/A3 | Issue B | `demo` / `nasa` + errores 503 | Feature (`Http::fake`) | NASA real: Pendiente de evidencia |

## 8.3 Milestone borrador — Demo Incendios MVP

**Nombre:** Demo Incendios MVP  

**Descripción:** Completar una funcionalidad web capaz de consultar focos (simulados de inmediato; NASA con clave) y visualizarlos sobre mapa, con API JSON propia para web y futura app móvil.

**Objetivo:** Demostrar el flujo completo Usuario → Web → API propia → (Demo o NASA FIRMS) → procesamiento → mapa/listado/detalle.

**Issues asociados (borrador):** A, B, C (ver documento 07).

**Criterios de finalización propuestos:**

1. Demo local ejecutable sin claves, con aviso de simulación.
2. API documentada y consumida por la web.
3. Períodos, confianza, selección y resumen operativos.
4. Errores NASA/mapa manejados sin mezclar fuentes.
5. Pruebas automatizadas del módulo en verde.
6. Issue + rama + PR + revisión de Jaziel + merge a `develop` con capturas — **Pendiente de evidencia**.
7. Enlace al repositorio — **Pendiente de evidencia**.

**Creación del Milestone en GitHub:** no realizada por Cursor.

## 8.4 Implementación vs planificación retrospectiva

| Hecho local (8 oct 2026) | Cómo documentarlo |
|--------------------------|-------------------|
| Codex implementó la demo con autorización de Josué | Hecho de implementación local |
| Pruebas automatizadas y Playwright locales | Comprobación técnica; no aprobación de Jaziel |
| Documentación académica generada después | Documentación retrospectiva / de apoyo |
| Operaciones Git/GitHub del equipo | Pendiente de evidencia |

**No presentar** la documentación posterior como si hubiera sido la planificación previa aprobada, salvo que exista esa evidencia.

## 8.5 Diferencias respecto a un diseño inicial React/Node

Si en material previo se mencionó React + Vite + Express, el estado **real del código** es:

- Frontend: Blade + JavaScript estático
- Backend: Laravel 12 (PHP)
- Sin Vite en la demo actual

La documentación de entrega debe seguir al código existente, no al diseño descartado.

## 8.6 Pedido de insumos al equipo

Para cerrar trazabilidad hacia el PDF, se necesitan:

1. Archivos o capturas de PERT, CPM y Planner **aprobados** (si ya existen).
2. URL del repositorio.
3. Número/URL del issue (o issues) reales.
4. Nombre de la rama `feature/...`.
5. Hashes o capturas de commits relevantes.
6. URL del PR y capturas.
7. Evidencia de revisión/aprobación de Jaziel.
8. Evidencia de merge a `develop`.
9. (Opcional) capturas de NASA/Google con claves reales, sin exponer secretos.

Hasta recibirlos, cada casilla correspondiente permanece como **Pendiente de evidencia**.
