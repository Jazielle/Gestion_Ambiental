const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs/promises');
const path = require('node:path');

(async () => {
    const url = process.env.GEOCRUZ_URL || 'http://127.0.0.1:8123';
    const destino = path.resolve(__dirname, '../docs/evidencias/p06-estaciones');
    await fs.mkdir(destino, { recursive: true });
    const browser = await chromium.launch({ headless: true, ...(process.env.GEOCRUZ_NAVEGADOR ? { executablePath: process.env.GEOCRUZ_NAVEGADOR } : {}) });
    const page = await browser.newPage({ viewport: { width: 1366, height: 900 } });
    const errores = [];
    page.on('pageerror', error => errores.push(error.message));
    const nombre = `Estación Centro P06 ${Date.now()}`;
    try {
        await page.goto(`${url}/estaciones`);
        await page.getByRole('link', { name: 'Registrar estación', exact: true }).click();
        await page.getByLabel('Nombre de la estación', { exact: true }).fill(nombre);
        await page.getByLabel('Latitud', { exact: true }).fill('-17.7833');
        await page.getByLabel('Longitud', { exact: true }).fill('-63.1821');
        await page.getByRole('button', { name: 'Registrar estación', exact: true }).click();
        await page.getByRole('status').filter({ hasText: 'Estación registrada correctamente.' }).waitFor();
        await page.getByRole('link', { name: `Editar ${nombre}`, exact: true }).click();
        assert.equal(await page.getByLabel('Nombre de la estación', { exact: true }).inputValue(), nombre);
        await page.screenshot({ path: path.join(destino, 'edicion_estacion.png'), fullPage: true });
        await page.getByLabel('Nombre de la estación', { exact: true }).fill(`${nombre} actualizada`);
        await page.getByLabel('Latitud', { exact: true }).fill('-17.7901');
        await page.getByRole('button', { name: 'Guardar cambios', exact: true }).click();
        await page.getByRole('status').filter({ hasText: 'Estación actualizada correctamente.' }).waitFor();
        await page.reload();
        const fila = page.getByRole('row').filter({ hasText: `${nombre} actualizada` });
        assert.match(await fila.textContent(), /-17\.7901000/);
        assert.match(await fila.textContent(), /-63\.1821000/);
        await page.screenshot({ path: path.join(destino, 'listado_estaciones.png'), fullPage: true });
        await page.setViewportSize({ width: 390, height: 844 });
        await page.getByRole('link', { name: `Editar ${nombre} actualizada`, exact: true }).click();
        assert.equal(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), true);
        assert.deepEqual(errores, []);
        const resultado = { resultado: 'aprobado', acciones: ['registrar desde formulario', 'confirmar listado', 'abrir edición con datos guardados', 'guardar cambios', 'recargar y comprobar persistencia', 'formulario de 390 px sin desbordamiento'], erroresJavaScript: errores };
        await fs.writeFile(path.join(destino, 'recorrido.json'), JSON.stringify(resultado, null, 2));
        console.log(JSON.stringify(resultado));
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exitCode = 1; });
