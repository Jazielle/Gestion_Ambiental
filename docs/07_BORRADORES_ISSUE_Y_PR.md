# 7. Borradores de Issue y Pull Request

**Uso:** copiar y pegar manualmente en GitHub.  
**Cursor no crea** issues, ramas, commits, PR ni merges.  
Los marcadores `[COMPLETAR: …]` deben llenarse con datos reales. **No inventar** números, hashes, aprobaciones ni fechas de merge.

Milestone sugerido (borrador): **Demo Incendios MVP**

---

## 7.1 Issue A — Configurar demo web de focos de calor

### Título

```
Configurar estructura web de GeoCruz para focos de calor en Santa Cruz
```

### Cuerpo

```markdown
## Descripción
Preparar la aplicación web Laravel 12 + Blade para demostrar el monitoreo de focos de calor en el departamento de Santa Cruz, con API JSON reutilizable por la futura app móvil.

## Objetivo
Dejar una base ejecutable localmente (sin claves externas) usando fuente de demostración y mapa local.

## Historia relacionada
HU-01 (borrador) — Visualizar focos de calor

## Checklist
- [ ] Proyecto web ejecutable con script o `php artisan serve`
- [ ] Módulo Incendios separado por responsabilidad
- [ ] Pantalla inicial de focos de calor
- [ ] Documentación mínima de arranque

## Prioridad
Alta

## Dependencias
Ninguna

## Milestone
Demo Incendios MVP

## Evidencias
- Issue: [COMPLETAR: URL del issue]
- Rama: [COMPLETAR: nombre y URL de la rama]
```

---

## 7.2 Issue B — API de focos (demo + preparación NASA)

### Título

```
Exponer API de focos de calor con fuente demo y soporte NASA FIRMS
```

### Cuerpo

```markdown
## Descripción
Implementar `GET /api/incendios` y `GET /api/incendios/limite` con validación de `dias` y `fuente`, normalización de campos en español, filtro territorial y manejo de errores. La fuente demo usa datos simulados; NASA requiere clave y no debe reemplazarse por simulados ante fallo.

## Objetivo
Entregar un contrato JSON estable para web y móvil.

## Historias relacionadas
HU-01, HU-02, HU-04, HU-06 (borradores)

## Checklist
- [ ] `dias=1|3|7` y `fuente=demo|nasa`
- [ ] Respuesta con `focos`, `resumen`, `consulta`
- [ ] `consulta.simulados` correcto
- [ ] 422 en parámetros inválidos
- [ ] 503 si NASA no está disponible o falta clave
- [ ] Pruebas Feature con respuestas NASA controladas

## Prioridad
Alta

## Dependencias
Issue A

## Milestone
Demo Incendios MVP

## Evidencias
- Issue: [COMPLETAR: URL]
- Prueba real NASA: Pendiente de evidencia
```

---

## 7.3 Issue C — Mapa, filtros, detalle y resumen

### Título

```
Visualizar focos en mapa con períodos, confianza y detalle
```

### Cuerpo

```markdown
## Descripción
Completar la interfaz: mapa (demostración o Google), períodos 24 h / 72 h / 7 días, filtro de confianza, listado, selección y resumen. Debe advertirse el modo simulado.

## Objetivo
Permitir explorar focos y su información sin confundir confianza con gravedad ni foco con incendio confirmado.

## Historias relacionadas
HU-01, HU-02, HU-03, HU-04, HU-05 (borradores)

## Checklist
- [ ] Marcadores seleccionables
- [ ] Detalle con fecha, coordenadas, satélite, instrumento, confianza, FRP
- [ ] Filtro de confianza
- [ ] Resumen de focos visibles
- [ ] Aviso visible de datos simulados
- [ ] Comportamiento usable en pantalla pequeña
- [ ] Fallback a mapa demo si Google no está disponible

## Prioridad
Alta

## Dependencias
Issue B

## Milestone
Demo Incendios MVP

## Evidencias
- Issue: [COMPLETAR: URL]
- Capturas UI locales: docs/evidencias/ (simuladas)
- Google real: Pendiente de evidencia
```

---

## 7.4 Plantilla de Pull Request

### Título sugerido

```
feat: demo web de focos de calor para Santa Cruz
```

### Cuerpo

```markdown
## Objetivo
Completar la demo académica end-to-end de focos de calor: API propia, fuente de demostración, preparación NASA FIRMS, mapa y filtros en Laravel 12 + Blade.

## Cambios
- Módulo `Incendios` en servidor (controlador, servicios, normalizador, geografía, datos demo)
- API `GET /api/incendios` y `GET /api/incendios/limite`
- Pantalla Blade + JS/CSS (mapa demo/Google, períodos, confianza, detalle, resumen)
- Pruebas Feature e instrucciones de ejecución local
- Documentación en `docs/`

## Fuera de alcance de este PR
- Aplicación móvil funcional
- Persistencia PostGIS
- Verificación externa con claves reales (salvo que se adjunte evidencia)

## Historias relacionadas
HU-01 … HU-06 (borradores en docs/02_HISTORIAS_DE_USUARIO.md)

## Issues relacionados
- [COMPLETAR: #A]
- [COMPLETAR: #B]
- [COMPLETAR: #C]

## Criterios de aceptación
- [ ] La demo inicia en local y muestra focos simulados con aviso visible
- [ ] Períodos 24 h, 72 h y 7 días actualizan la consulta
- [ ] El filtro de confianza altera mapa, lista y resumen
- [ ] Selección de marcador/fila muestra detalle
- [ ] NASA sin clave responde error y no mezcla simulados
- [ ] Las pruebas automatizadas del módulo pasan
- [ ] No se exponen secretos de `.env` en el PR

## Cómo probar
1. `powershell -ExecutionPolicy Bypass -File .\herramientas\iniciar-demo.ps1`
2. Abrir la URL local
3. Probar períodos, confianza, selección y actualizar
4. Probar fuente NASA sin clave (debe fallar claro)
5. Opcional: `herramientas\probar-demo.ps1`

## Revisión
- Autor implementación: [COMPLETAR]
- Revisor: Jaziel Díaz — [COMPLETAR: enlace al comentario/aprobación]
- Estado de revisión: Pendiente de evidencia

## Merge
- Destino previsto: `develop`
- Merge realizado: Pendiente de evidencia
- Commit/hash: [COMPLETAR: no inventar]
```

---

## 7.5 Ramas sugeridas (solo nombres documentales)

| Rama | Uso |
|------|-----|
| `main` | Versión estable |
| `develop` | Integración |
| `feature/incendios-demo-web` | Ejemplo para esta demo |

Flujo: `feature/*` → PR → `develop`.  
Estabilización posterior: `develop` → PR → `main`.

**Creación real de ramas:** Pendiente de evidencia (acción manual de Josué).

---

## 7.6 Mensajes de commit sugeridos (guía)

```
feat: agregar módulo de focos de calor con API demo y preparación NASA
feat: visualizar focos en mapa con filtros de período y confianza
test: cubrir consulta de incendios y errores de NASA controlados
docs: documentar alcance, HU, CU y guía de evidencias
```

No ejecutar commits desde Cursor; son textos para uso manual.
