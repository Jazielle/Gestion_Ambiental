# 6. Guion del PDF de evidencias

Este guion organiza el PDF académico. Las capturas y enlaces reales los aporta el equipo. Todo lo ausente queda como **Pendiente de evidencia**; no se inventan números de issue, hashes, aprobaciones ni merges.

## Portada (sugerida)

- Título: GeoCruz – Demo de focos de calor · Departamento de Santa Cruz
- Universidad Privada del Valle – UNIVALLE
- Carrera: Ingeniería en Sistemas Informáticos
- Materia: Proyecto de Sistemas III
- Docente: Yasmani Fernandez
- Integrantes: Josué Padilla · Jaziel Díaz
- Sede: Santa Cruz · Gestión: 2026
- Enlace al repositorio: **Pendiente de evidencia**

---

## Sección A — ¿Qué debíamos hacer?

**Contenido textual (usar docs 01–03):**

- Objetivo de la demo: consultar y visualizar focos de calor / posibles incendios en Santa Cruz.
- Alcance geográfico: departamento completo.
- Stack real: Laravel 12 + Blade + API JSON.
- Precisión: foco térmico ≠ incendio confirmado; confianza ≠ gravedad.

**Evidencia a insertar:**

| Evidencia | Estado |
|-----------|--------|
| Enunciado o rúbrica del docente (si aplica) | Pendiente de evidencia |
| HU/CU borrador o versión aprobada | Borradores en `02` y `03`; aprobación formal pendiente |

---

## Sección B — ¿Dónde desarrollamos?

**Contenido:**

- Repositorio local / remoto.
- Rama de trabajo prevista: `feature/...` → PR → `develop` (y luego `main` al estabilizar).
- Módulo: `web/app/Modulos/Incendios/` y `web/public/modulos/incendios/`.

**Evidencia a insertar:**

| Evidencia | Estado |
|-----------|--------|
| Captura del repositorio en GitHub | Pendiente de evidencia |
| Captura de la rama utilizada | Pendiente de evidencia |
| Enlace del repositorio | Pendiente de evidencia |

---

## Sección C — ¿Qué cambiamos?

**Contenido:** resumir [REGISTRO_CAMBIOS.md](REGISTRO_CAMBIOS.md).

Puntos clave:

- Creación del módulo Incendios (servicios, normalizador, geografía, demo).
- Pantalla Blade + JS/CSS estáticos.
- API `/api/incendios` y `/api/incendios/limite`.
- Pruebas Feature y herramientas PowerShell.
- Retiro de Vite/welcome de ejemplo.

**Evidencia a insertar:**

| Evidencia | Estado |
|-----------|--------|
| Capturas de commits | Pendiente de evidencia |
| Lista de archivos tocados (puede citarse el registro) | Disponible en docs |
| Diff o historial en GitHub | Pendiente de evidencia |

---

## Sección D — ¿Quién participó?

| Rol | Persona | Evidencia |
|-----|---------|-----------|
| Web | Josué Padilla | Pendiente de evidencia (commits/PR autoría) |
| Móvil + revisión web | Jaziel Díaz | Móvil aún no implementada; revisión Pendiente de evidencia |
| Implementación de la demo | Codex (autorizada por Josué) | Registro local 8 oct 2026; no sustituye Git del equipo |

---

## Sección E — ¿Quién revisó?

**Texto base:** Jaziel Díaz revisa la web. Las pruebas de Codex **no** son su aprobación.

| Evidencia | Estado |
|-----------|--------|
| Comentario de revisión en el PR | Pendiente de evidencia |
| Aprobación explícita de Jaziel | Pendiente de evidencia |
| Captura de la revisión | Pendiente de evidencia |

---

## Sección F — ¿Cómo comprobamos que funciona?

**Contenido:**

1. Pruebas automatizadas locales ([RESULTADOS_PRUEBAS.md](RESULTADOS_PRUEBAS.md)).
2. Verificación de interfaz (Playwright) con datos **simulados**.
3. Guía manual ([05_GUIA_PRUEBA_MANUAL.md](05_GUIA_PRUEBA_MANUAL.md)).
4. Capturas:
   - `evidencias/demo-escritorio.png`
   - `evidencias/demo-pantalla-pequena.png`

**Debe decirse en el PDF:**

- Estas pruebas demuestran el flujo local con fuente Demostración y/o NASA falseada.
- No equivalen a NASA/Google verificados en entorno con claves reales, salvo que se agregue esa evidencia.

| Evidencia adicional | Estado |
|---------------------|--------|
| Prueba NASA con clave real | Pendiente de evidencia |
| Prueba Google Maps con clave real | Pendiente de evidencia |
| Checklist manual firmado por el equipo | Pendiente de evidencia |

---

## Sección G — ¿Cómo se integró?

Flujo docente documentado (sin ejecutarlo desde Cursor):

```
feature/*  →  Pull Request  →  develop  →  (estabilización)  →  main
```

**Evidencia a insertar:**

| Evidencia | Estado |
|-----------|--------|
| Captura del issue | Pendiente de evidencia |
| Captura de la rama | Pendiente de evidencia |
| Captura del PR | Pendiente de evidencia |
| Captura de aprobación | Pendiente de evidencia |
| Captura del merge a `develop` | Pendiente de evidencia |
| Enlace al PR | Pendiente de evidencia |

Textos listos para copiar: [07_BORRADORES_ISSUE_Y_PR.md](07_BORRADORES_ISSUE_Y_PR.md).

---

## Sección H — Planificación y trazabilidad

Incluir o adjuntar:

- Orden docente: PERT → CPM → Planner → HU → CU → Issues → Milestone → ramas → PR.
- Matriz de [08_TRAZABILIDAD_Y_PLANIFICACION.md](08_TRAZABILIDAD_Y_PLANIFICACION.md).

| Planificación aprobada (PERT/CPM/Planner) | Pendiente de evidencia |
| HU/CU aprobados formalmente | Pendiente de evidencia (hay borradores) |

**Aviso metodológico para el PDF:** documentar después del código no debe presentarse como si la planificación hubiera existido antes, salvo que sí exista esa evidencia previa.

---

## Sección I — Precisiones finales (obligatorias en conclusiones)

1. Fuente Demostración = datos simulados.
2. NASA y Google = preparados; verificación real pendiente si no hay capturas.
3. Confianza del sensor ≠ gravedad.
4. Foco térmico ≠ incendio confirmado.
5. Vista móvil web ≠ aplicación móvil de Jaziel.

---

## Checklist de armado del PDF

- [ ] Portada con datos institucionales
- [ ] Enlace al repositorio (real)
- [ ] Qué debíamos hacer
- [ ] Dónde desarrollamos (rama + repo)
- [ ] Qué cambiamos (commits + registro)
- [ ] Quién participó
- [ ] Quién revisó (Jaziel)
- [ ] Cómo se probó (incluir aviso de simulación)
- [ ] Cómo se integró (issue → PR → merge)
- [ ] Trazabilidad a planificación
- [ ] Conclusiones con limitaciones

Todo ítem sin captura o enlace: dejar explícitamente **Pendiente de evidencia**.
