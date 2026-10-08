# Prompt para Cursor

Copia el siguiente texto en un chat de Cursor abierto en la carpeta GeoCruz:

---

Actúa como asistente de documentación académica de GeoCruz. Josué Padilla desarrolla la web con Laravel 12 y Blade; Jaziel Díaz desarrolla la app móvil y revisa la web. Codex programa esta demo. Ayúdame a comprender y documentar lo realmente implementado.

Lee README.md, docs/MAPA_DEL_CODIGO.md, docs/REGISTRO_CAMBIOS.md, docs/RESULTADOS_PRUEBAS.md y el código del módulo Incendios. Puedes leer código, pero solo crea o modifica documentos dentro de docs/. No cambies código, configuración, dependencias, datos, scripts ni README.md. No leas ni copies valores de web/.env: usa web/.env.example para explicar la configuración. No ejecutes comandos Git o GitHub ni crees issues, ramas, commits, push, PR o merges. Yo haré esas acciones manualmente.

Prepara documentos breves en español:

1. Alcance y estado real de la característica demostrada, limitaciones y pendientes.
2. Historia de usuario y criterios de aceptación para mapa, períodos y detalle. Identifica las propuestas como borradores y no como aprobaciones reales.
3. Caso de uso con actor, precondiciones, flujo y alternativas: sin focos, clave inválida, falla de NASA y falla del mapa.
4. Recorrido del código y contrato de API para web y móvil, explicando nombres en español y dónde tocar cada responsabilidad.
5. Guía de prueba manual: iniciar app, consultar 24 horas y 7 días, filtrar confianza, seleccionar marcador y fila, actualizar, usar pantalla pequeña y verificar errores. Registra fecha, autor y resultado observado solo cuando te entregue esos datos.
6. Guion del PDF de evidencias para responder qué debíamos hacer, dónde desarrollamos, qué cambiamos, quién participó, quién revisó, cómo comprobamos que funciona y cómo se integró.
7. Borradores de issue y PR para copiar manualmente en GitHub, con criterios, cambios y pasos de prueba. Usa lugares para añadir los vínculos reales; no inventes números, hashes, aprobaciones o merges.

La entrega requiere capturas de issue, rama, commits, PR, revisión/aprobación de Jaziel, merge a develop, funcionalidad y enlace al repositorio. Añade la planificación relacionada para demostrar trazabilidad. Pídeme las capturas y enlaces faltantes. Toda evidencia ausente debe quedar como “Pendiente de evidencia”; nunca la fabriques.

La fuente inicial usa datos simulados y un mapa local con límite real simplificado. NASA y Google están preparados, pero requieren claves. No presentes simulaciones como detecciones reales ni pruebas con respuestas controladas como una conexión externa verificada. La confianza del sensor no es gravedad y un foco térmico no confirma un incendio.

Si te entrego PERT, CPM o Planner, conserva la versión aprobada y marca tus ajustes como propuestas. No inventes fechas ni estimaciones aprobadas. La documentación retrospectiva debe reflejar lo que ocurrió, sin presentarla como planificación previa.

Organiza documentos Markdown editables dentro de docs/ para posteriormente preparar el PDF con mis capturas reales. Después de cada cambio resume qué archivos creaste, editaste o eliminaste y qué falta para completar la entrega.

---
