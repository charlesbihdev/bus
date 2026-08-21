import { Head, Link } from '@inertiajs/react';
import { index, show } from '@/actions/App/Http/Controllers/Admin/BookingController';
import { cedis } from '@/lib/format';

interface BookingRow {
    id: number;
    reference: string;
    route: string;
    date: string;
    contact_name: string;
    seats: number;
    amount: number;
    status: string;
}

const STATUS: Record<string, string> = {
    paid: 'text-emerald-600',
    pending: 'text-amber-600',
    cancelled: 'text-muted-foreground',
    expired: 'text-muted-foreground',
};

export default function BookingsIndex({ bookings }: { bookings: BookingRow[] }) {
    return (
        <>
            <Head title="Bookings" />
            <div className="p-4 md:p-8">
                <h1 className="mb-6 text-2xl font-bold tracking-tight">Bookings</h1>

                <div className="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left text-xs text-muted-foreground uppercase">
                            <tr>
                                <th className="px-4 py-3 font-medium">Reference</th>
                                <th className="px-4 py-3 font-medium">Route</th>
                                <th className="px-4 py-3 font-medium">Date</th>
                                <th className="px-4 py-3 font-medium">Passenger</th>
                                <th className="px-4 py-3 font-medium">Seats</th>
                                <th className="px-4 py-3 font-medium">Amount</th>
                                <th className="px-4 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {bookings.map((b) => (
                                <tr key={b.id} className="border-t border-sidebar-border/70 hover:bg-muted/40 dark:border-sidebar-border">
                                    <td className="px-4 py-3">
                                        <Link href={show(b.id)} className="font-mono text-xs font-medium hover:underline">{b.reference}</Link>
                                    </td>
                                    <td className="px-4 py-3 font-medium">{b.route}</td>
                                    <td className="px-4 py-3 text-muted-foreground">{b.date}</td>
                                    <td className="px-4 py-3 text-muted-foreground">{b.contact_name}</td>
                                    <td className="px-4 py-3 text-muted-foreground">{b.seats}</td>
                                    <td className="px-4 py-3 font-medium">{cedis(b.amount)}</td>
                                    <td className={`px-4 py-3 capitalize ${STATUS[b.status] ?? ''}`}>{b.status}</td>
                                </tr>
                            ))}
                            {bookings.length === 0 && (
                                <tr><td colSpan={7} className="px-4 py-10 text-center text-muted-foreground">No bookings yet.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

BookingsIndex.layout = {
    breadcrumbs: [{ title: 'Bookings', href: index() }],
};
