/** Money is stored server-side as integer pesewas (GHS * 100). */
export function cedis(pesewas: number): string {
    return new Intl.NumberFormat('en-GH', {
        style: 'currency',
        currency: 'GHS',
        minimumFractionDigits: 2,
    }).format(pesewas / 100);
}

/** 24h "HH:mm" -> 12h "h:mm AM/PM" (e.g. "06:00" -> "6:00 AM", "14:00" -> "2:00 PM"). */
export function time12(hhmm: string): string {
    const [h, m] = hhmm.split(':').map(Number);

    if (Number.isNaN(h) || Number.isNaN(m)) {
        return hhmm;
    }

    const period = h < 12 ? 'AM' : 'PM';
    const hour = h % 12 || 12;

    return `${hour}:${m.toString().padStart(2, '0')} ${period}`;
}

/** e.g. 300 -> "5h 0m", 50 -> "50m" */
export function duration(minutes: number | null | undefined): string {
    if (!minutes) {
        return '';
    }

    const h = Math.floor(minutes / 60);
    const m = minutes % 60;

    return h ? `${h}h ${m}m` : `${m}m`;
}
