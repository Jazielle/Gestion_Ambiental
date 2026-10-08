// Formatos de presentación. Todas las fechas se reciben con zona horaria explícita.
export function formatearFecha(fecha) {
    return new Intl.DateTimeFormat('es-BO', {
        timeZone: 'America/La_Paz',
        day: '2-digit',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    }).format(new Date(fecha));
}

export function formatearPotencia(potencia) {
    return potencia == null
        ? 'No disponible'
        : `${Number(potencia).toLocaleString('es-BO', { maximumFractionDigits: 1 })} MW`;
}

export function nombreConfianza(confianza) {
    return (
        {
            alta: 'Alta',
            nominal: 'Nominal',
            baja: 'Baja',
            desconocida: 'Desconocida',
        }[confianza] ?? 'Desconocida'
    );
}

export function colorConfianza(confianza) {
    return (
        {
            alta: '#dc502e',
            nominal: '#e9a039',
            baja: '#628da0',
            desconocida: '#687787',
        }[confianza] ?? '#687787'
    );
}
