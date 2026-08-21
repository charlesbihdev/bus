import { Head } from '@inertiajs/react';
import { index } from '@/actions/App/Http/Controllers/Admin/BookingController';
import { cedis, time12 } from '@/lib/format';

interface Seat {
    label: string;
    passenger_name: string | null;
}

interface Booking {
    reference: string;
    status: string;
    route: string;
    date: string;
    departure_time: string;
    bus: string;
    contact_name: string;
    contact_phone: string;
    total_amount: number;
    seats: Seat[];
}

export default function BookingShow({ booking }: { booking: Booking }) {
    return (
        <>
            <Head title={booking.reference} />
            <div className="max-w-2xl p-4 md:p-8">
                <div className="mb-1 flex items-center gap-3">
                    <h1 className="text-2xl font-bold tracking-tight">{booking.route}</h1>
                    <span className="rounded-full bg-muted px-2.5 py-0.5 text-xs font-semibold capitalize">{booking.status}</span>
                </div>
                <p className="mb-6 text-sm text-muted-foreground">
                    {booking.reference} · {booking.date} · {time12(booking.departure_time)} · {booking.bus}
                </p>

                <dl className="grid grid-cols-2 gap-4 rounded-xl border border-sidebar-border/70 p-5 text-sm dark:border-sidebar-border">
                    <Field label="Passenger">{booking.contact_name}</Field>
                    <Field label="Phone">{booking.contact_phone}</Field>
                    <Field label="Total">{cedis(booking.total_amount)}</Field>
                    <Field label="Seats">{booking.seats.length}</Field>
                </dl>

                <h2 className="mt-6 mb-2 text-sm font-semibold">Manifest</h2>
                <div className="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="w-full text-sm">
                        <tbody>
                            {booking.seats.map((s) => (
                                <tr key={s.label} className="border-t border-sidebar-border/70 first:border-t-0 dark:border-sidebar-border">
                                    <td className="px-4 py-2.5 font-semibold">Seat {s.label}</td>
                                    <td className="px-4 py-2.5 text-muted-foreground">{s.passenger_name ?? booking.contact_name}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

function Field({ label, children }: { label: string; children: React.ReactNode }) {
    return (
        <div>
            <dt className="text-xs text-muted-foreground uppercase">{label}</dt>
            <dd className="mt-0.5 font-medium">{children}</dd>
        </div>
    );
}

BookingShow.layout = {
    breadcrumbs: [{ title: 'Bookings', href: index() }],
};
