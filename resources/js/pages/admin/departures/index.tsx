import { Head, router } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { useState } from 'react';
import { cancel, reopen } from '@/actions/App/Http/Controllers/Admin/DepartureController';
import { index as routes } from '@/actions/App/Http/Controllers/Admin/RouteController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { time12 } from '@/lib/format';

interface Schedule {
    id: number;
    origin: string;
    destination: string;
    departure_time: string;
    bus: string;
}

interface DepartureDate {
    date: string;
    status: 'scheduled' | 'cancelled';
    seats_booked: number;
}

function post(url: string, from: string, to: string) {
    router.post(url, { from, to }, { preserveScroll: true });
}

export default function DeparturesIndex({ schedule, dates }: { schedule: Schedule; dates: DepartureDate[] }) {
    const [from, setFrom] = useState(dates[0]?.date ?? '');
    const [to, setTo] = useState(dates[dates.length - 1]?.date ?? '');

    return (
        <>
            <Head title="Departure dates" />
            <div className="p-4 md:p-8">
                <div className="mb-1 flex items-center gap-2 text-xl font-bold tracking-tight">
                    {schedule.origin} <ArrowRight className="size-5 text-brand-red" /> {schedule.destination}
                </div>
                <p className="mb-6 text-sm text-muted-foreground">{time12(schedule.departure_time)} · {schedule.bus} · next {dates.length} days</p>

                <div className="mb-6 flex flex-wrap items-end gap-3 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <div className="space-y-1.5">
                        <Label htmlFor="from">From</Label>
                        <Input id="from" type="date" value={from} onChange={(e) => setFrom(e.target.value)} className="w-auto" />
                    </div>
                    <div className="space-y-1.5">
                        <Label htmlFor="to">To</Label>
                        <Input id="to" type="date" value={to} onChange={(e) => setTo(e.target.value)} className="w-auto" />
                    </div>
                    <Button variant="outline" className="text-brand-red" onClick={() => post(cancel.url(schedule.id), from, to)}>
                        Cancel range
                    </Button>
                    <Button variant="outline" onClick={() => post(reopen.url(schedule.id), from, to)}>
                        Reopen range
                    </Button>
                </div>

                <div className="grid gap-2 sm:grid-cols-2">
                    {dates.map((d) => (
                        <div
                            key={d.date}
                            className="flex items-center justify-between rounded-lg border border-sidebar-border/70 px-4 py-3 dark:border-sidebar-border"
                        >
                            <div>
                                <p className="font-medium">{d.date}</p>
                                <p className="text-xs text-muted-foreground">
                                    {d.status === 'cancelled' ? 'Cancelled' : 'Running'} · {d.seats_booked} seat(s) booked
                                </p>
                            </div>
                            {d.status === 'cancelled' ? (
                                <Button variant="ghost" size="sm" onClick={() => post(reopen.url(schedule.id), d.date, d.date)}>
                                    Reopen
                                </Button>
                            ) : (
                                <Button variant="ghost" size="sm" className="text-brand-red" onClick={() => post(cancel.url(schedule.id), d.date, d.date)}>
                                    Cancel
                                </Button>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}

DeparturesIndex.layout = {
    breadcrumbs: [
        { title: 'Routes', href: routes() },
        { title: 'Dates', href: '#' },
    ],
};
