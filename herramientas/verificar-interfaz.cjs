// Verificación automatizada del navegador. Requiere Playwright instalado; no se usa para iniciar la demo.
const { chromium: navegadorChromium } = require('playwright');
const comprobar = require('node:assert/strict');
const archivos = require('node:fs/promises');
const rutas = require('node:path');

async function verificarInterfaz() {
    const direccion = process.env.GEOCRUZ_URL || 'http://127.0.0.1:8123';
    const carpetaEvidencias = rutas.resolve(__dirname, '../docs/evidencias');
    await archivos.mkdir(carpetaEvidencias, { recursive: true });
    const navegador = await navegadorChromium.launch({
        headless: true,
        executablePath:
            process.env.GEOCRUZ_NAVEGADOR || navegadorChromium.executablePath(),
    });
    const errores = [];
    const contexto = await navegador.newContext({
        viewport: { width: 1440, height: 1000 },
    });
    const pagina = await contexto.newPage();
    pagina.on('pageerror', (error) => errores.push(error.message));
    try {
        await pagina.goto(direccion, { waitUntil: 'networkidle' });
        await pagina.locator('.fila-foco').first().waitFor();
        const totalDia = await pagina.locator('.fila-foco').count();
        comprobar.ok(totalDia > 0);
        await pagina.locator('.marcador-foco').first().click();
        comprobar.equal(
            await pagina.locator('#detalle-foco').isVisible(),
            true,
        );
        comprobar.ok(
            (await pagina.locator('#detalle-satelite').textContent()).includes(
                'simulado',
            ),
        );

        await pagina
            .getByRole('button', { name: '7 días', exact: true })
            .click();
        await pagina.waitForFunction(
            (totalAnterior) =>
                Number(document.getElementById('resumen-total').textContent) >
                totalAnterior,
            totalDia,
        );
        const totalSemana = await pagina.locator('.fila-foco').count();
        comprobar.ok(totalSemana > totalDia);
        await pagina.locator('#filtro-confianza').selectOption('alta');
        comprobar.equal(
            await pagina
                .locator('.fila-foco .insignia-confianza:not(.alta)')
                .count(),
            0,
        );
        comprobar.equal(
            Number(await pagina.locator('#resumen-total').textContent()),
            await pagina.locator('.fila-foco').count(),
        );
        await pagina.locator('.fila-foco').first().click();
        await pagina
            .getByRole('button', { name: 'Acercar mapa', exact: true })
            .click();
        comprobar.notEqual(
            await pagina.locator('.mapa-demostracion').getAttribute('viewBox'),
            '0 0 960 610',
        );
        await pagina
            .getByRole('button', { name: 'Ver departamento', exact: true })
            .click();

        // Verificar error de configuración real y que no se mantienen resultados simulados.
        await pagina.locator('#filtro-fuente').selectOption('nasa');
        await pagina.locator('#mensaje-error').waitFor({ state: 'visible' });
        comprobar.ok(
            (await pagina.locator('#mensaje-error').textContent()).includes(
                'NASA_FIRMS_CLAVE',
            ),
        );
        comprobar.equal(await pagina.locator('.fila-foco').count(), 0);
        comprobar.equal(
            await pagina.locator('#detalle-foco').isVisible(),
            false,
        );

        // Respuesta vacía controlada, equivalente a una consulta válida sin focos.
        await pagina.route('**/api/incendios?*', (ruta) =>
            ruta.fulfill({
                json: {
                    focos: [],
                    resumen: { total: 0 },
                    consulta: {
                        fuente: 'demo',
                        simulados: true,
                        dias: 7,
                        fechaConsulta: new Date().toISOString(),
                    },
                },
            }),
        );
        await pagina.locator('#filtro-fuente').selectOption('demo');
        await pagina.waitForFunction(
            () => document.getElementById('resumen-total').textContent === '0',
        );
        comprobar.ok(
            (await pagina.locator('#lista-focos').textContent()).includes(
                'No hay detecciones',
            ),
        );
        await pagina.unroute('**/api/incendios?*');
        await pagina.locator('#filtro-confianza').selectOption('todas');
        await pagina
            .getByRole('button', { name: 'Actualizar datos', exact: true })
            .click();
        await pagina.locator('.fila-foco').first().waitFor();
        await pagina.locator('.fila-foco').first().click();
        await pagina.screenshot({
            path: rutas.join(carpetaEvidencias, 'demo-escritorio.png'),
            fullPage: true,
        });
        comprobar.equal(
            await pagina.evaluate(
                () => document.documentElement.scrollWidth > window.innerWidth,
            ),
            false,
        );
        comprobar.deepEqual(errores, []);

        const contextoMovil = await navegador.newContext({
            viewport: { width: 390, height: 844 },
            isMobile: true,
            deviceScaleFactor: 1,
        });
        const paginaMovil = await contextoMovil.newPage();
        paginaMovil.on('pageerror', (error) => errores.push(error.message));
        await paginaMovil.goto(direccion, { waitUntil: 'networkidle' });
        await paginaMovil.locator('.fila-foco').first().waitFor();
        await paginaMovil.locator('.fila-foco').first().click();
        comprobar.equal(
            await paginaMovil.evaluate(
                () => document.documentElement.scrollWidth > window.innerWidth,
            ),
            false,
        );
        comprobar.equal(
            await paginaMovil.locator('#detalle-foco').isVisible(),
            true,
        );
        await paginaMovil.screenshot({
            path: rutas.join(carpetaEvidencias, 'demo-pantalla-pequena.png'),
            fullPage: true,
        });
        comprobar.deepEqual(errores, []);
        await contextoMovil.close();
        console.log(
            JSON.stringify(
                {
                    resultado: 'correcto',
                    focos24Horas: totalDia,
                    focos7Dias: totalSemana,
                    erroresJavaScript: errores.length,
                    pruebas: [
                        'marcador y detalle',
                        'período de 7 días',
                        'confianza y resumen',
                        'zoom',
                        'NASA sin clave',
                        'sin detecciones',
                        'actualizar',
                        'pantalla de 390 px sin desbordamiento',
                    ],
                },
                null,
                2,
            ),
        );
    } finally {
        await contexto.close();
        await navegador.close();
    }
}

verificarInterfaz().catch((error) => {
    console.error(error);
    process.exitCode = 1;
});
