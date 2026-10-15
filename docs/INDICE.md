# Índice de documentación — GeoCruz

**Proyecto:** GeoCruz – Sistema de Monitoreo Ambiental del Departamento de Santa Cruz  
**Institución:** Universidad Privada del Valle – UNIVALLE  
**Materia:** Proyecto de Sistemas III · **Gestión:** 2026  
**Integrantes:** Josué Padilla (web), Jaziel Díaz (móvil y revisión web)  
**Docente:** Yasmani Fernandez

Este índice organiza la documentación editable en `docs/` para armar después el PDF de evidencias con capturas reales.

## Documentos de implementación (ya existentes)

| Documento | Contenido |
|-----------|-----------|
| [MAPA_DEL_CODIGO.md](MAPA_DEL_CODIGO.md) | Dónde está cada responsabilidad del módulo Incendios |
| [REGISTRO_CAMBIOS.md](REGISTRO_CAMBIOS.md) | Archivos creados, editados o eliminados en la demo |
| [RESULTADOS_PRUEBAS.md](RESULTADOS_PRUEBAS.md) | Comprobaciones locales (simuladas / controladas) |
| [PROMPT_PARA_CURSOR.md](PROMPT_PARA_CURSOR.md) | Instrucciones para el asistente de documentación |
| [evidencias/](evidencias/) | Capturas locales de la demo web |

## Documentos académicos (elaborados para la entrega)

| Orden | Documento | Propósito |
|------:|-----------|-----------|
| 1 | [01_ALCANCE_Y_ESTADO.md](01_ALCANCE_Y_ESTADO.md) | Qué se demostró, limitaciones y pendientes |
| 2 | [02_HISTORIAS_DE_USUARIO.md](02_HISTORIAS_DE_USUARIO.md) | HU borrador + criterios de aceptación |
| 3 | [03_CASO_DE_USO.md](03_CASO_DE_USO.md) | CU-01 y flujos alternativos |
| 4 | [04_RECORRIDO_CODIGO_Y_API.md](04_RECORRIDO_CODIGO_Y_API.md) | Recorrido del código y contrato JSON |
| 5 | [05_GUIA_PRUEBA_MANUAL.md](05_GUIA_PRUEBA_MANUAL.md) | Pasos de prueba manual y registro de resultados |
| 6 | [06_GUION_PDF_EVIDENCIAS.md](06_GUION_PDF_EVIDENCIAS.md) | Guion del PDF de entrega |
| 7 | [07_BORRADORES_ISSUE_Y_PR.md](07_BORRADORES_ISSUE_Y_PR.md) | Textos para copiar en GitHub |
| 8 | [08_TRAZABILIDAD_Y_PLANIFICACION.md](08_TRAZABILIDAD_Y_PLANIFICACION.md) | Trazabilidad y estado de planificación |

## Orden docente (referencia)

PERT → CPM → Planner → Historias de Usuario → Casos de Uso → Issues → Milestone → ramas (`main` / `develop` / `feature`) → Pull Request.

Las HU, CU, Issues y PR de esta carpeta están alineados con la **demo implementada**. PERT, CPM y Planner aprobados: **Pendiente de evidencia** (no inventados aquí).

## Precisión terminológica

- Se habla de **foco de calor**, **detección térmica** o **posible incendio**.
- Un foco térmico **no confirma** un incendio.
- La **confianza** es del sensor; **no** expresa gravedad.
- La fuente **Demostración** usa datos **simulados**.
- NASA FIRMS y Google Maps están **preparados** y requieren claves; no se presentan como conexiones externas verificadas mientras falte esa evidencia.
