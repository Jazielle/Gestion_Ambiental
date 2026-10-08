<section class="barra-filtros" aria-label="Filtros de consulta">
    <div class="grupo-periodo">
        <span class="etiqueta-filtro" id="etiqueta-periodo">Período</span>
        <div
            class="selector-periodo"
            role="group"
            aria-labelledby="etiqueta-periodo"
        >
            <button type="button" data-dias="1" aria-pressed="true">
                24 horas</button
            ><button type="button" data-dias="3" aria-pressed="false">
                72 horas</button
            ><button type="button" data-dias="7" aria-pressed="false">
                7 días
            </button>
        </div>
    </div>
    <label class="campo-filtro" for="filtro-confianza"
        >Confianza<select id="filtro-confianza">
            <option value="todas">Todas</option>
            <option value="alta">Alta</option>
            <option value="nominal">Nominal</option>
            <option value="baja">Baja</option>
        </select></label
    >
    <label class="campo-filtro" for="filtro-fuente"
        >Fuente de datos<select id="filtro-fuente">
            <option value="demo">Demostración</option>
            <option value="nasa">NASA FIRMS</option>
        </select></label
    >
    <span class="fecha-actualizacion" id="fecha-actualizacion"
        >Preparando consulta…</span
    >
</section>
