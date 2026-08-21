import { Head, Link } from '@inertiajs/react';
import { Bus, Map, Plus, Ticket, TicketCheck, Wallet } from 'lucide-react';
import { create, index as routes } from '@/actions/App/Http/Controllers/Admin/RouteController';
import { Button } from '@/components/ui/button';
import { cedis } from '@/lib/format';
import { dashboard } from '@/routes';

interface Stats {
    active_trips: number;
    total_trips: number;
    paid_bookings: number;
    pending_holds: number;
    seats_sold: number;
    revenue: number;
}

interface RecentBooking {
    reference: string;
    route: string;
    date: string;
    seats: number;
    amount: number;
    status: string;
}

const STATUS_STYLES: Record<string, string> = {
    paid: 'text-emerald-600',
    pending: 'text-amber-600',
    cancelled: 'text-muted-foreground',
    expired: 'text-muted-foreground',
};

export default function Dashboard({ stats, recent }: { stats: Stats; recent: RecentBooking[] }) {
    const cards = [
        { label: 'Active trips', value: `${stats.active_trips}`, hint: `${stats.total_trips} total`, icon: Bus },
        { label: 'Seats sold', value: `${stats.seats_sold}`, hint: `${stats.paid_bookings} paid bookings`, icon: TicketCheck },
        { label: 'Pending holds', value: `${stats.pending_holds}`, hint: 'awaiting payment', icon: Ticket },
        { label: 'Revenue', value: cedis(stats.revenue), hint: 'from paid bookings', icon: Wallet },
    ];

    return (
        <>
            <Head title="Overview" />
            <div className="flex flex-col gap-6 p-4 md:p-8">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold tracking-tight">Overview</h1>
                    <div className="flex gap-2">
                        <Button asChild variant="outline"><Link href={routes()}><Map className="size-4" /> Manage routes</Link></Button>
                        <Button asChild><Link href={create()}><Plus className="size-4" /> New route</Link></Button>
                    </div>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {cards.map((c) => (
                        <div key={c.label} className="rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border">
                            <div className="flex items-center justify-between text-muted-foreground">
                                <span className="text-xs font-medium tracking-wide uppercase">{c.label}</span>
                                <c.icon className="size-4" />
                            </div>
                            <p className="mt-3 text-2xl font-bold text-neutral-900 dark:text-white">{c.value}</p>
                            <p className="mt-1 text-xs text-muted-foreground">{c.hint}</p>
                        </div>
                    ))}
                </div>

                <div className="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div className="border-b border-sidebar-border/70 px-5 py-3 text-sm font-semibold dark:border-sidebar-border">Recent bookings</div>
                    {recent.length === 0 ? (
                        <p className="px-5 py-10 text-center text-sm text-muted-foreground">No bookings yet.</p>
                    ) : (
                        <table className="w-full text-sm">
                            <tbody>
                                {recent.map((b) => (
                                    <tr key={b.reference} className="border-t border-sidebar-border/70 first:border-t-0 dark:border-sidebar-border">
                                        <td className="px-5 py-3 font-mono text-xs text-muted-foreground">{b.reference}</td>
                                        <td className="px-5 py-3 font-medium">{b.route}</td>
                                        <td className="px-5 py-3 text-muted-foreground">{b.date}</td>
                                        <td className="px-5 py-3 text-muted-foreground">{b.seats} seat(s)</td>
                                        <td className="px-5 py-3 font-medium">{cedis(b.amount)}</td>
                                        <td className={`px-5 py-3 capitalize ${STATUS_STYLES[b.status] ?? ''}`}>{b.status}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [{ title: 'Overview', href: dashboard() }],
};
