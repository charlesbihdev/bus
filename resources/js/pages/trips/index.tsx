import { Head } from '@inertiajs/react';
import { useState } from 'react';
import { TripCard } from '@/components/booking/trip-card';
import type { Corridor } from '@/components/booking/trip-card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

function tomorrow(): string {
    const d = new Date();
    d.setDate(d.getDate() + 1);

    return d.toISOString().slice(0, 10);
}

export default function TripsIndex({ corridors }: { corridors: Corridor[] }) {
    const [date, setDate] = useState(tomorrow());

    return (
        <>
            <Head title="Book a trip" />
            <div className="mx-auto w-full max-w-3xl p-4 md:p-8">
                <header className="mb-8">
                    <h1 className="text-3xl font-bold tracking-tight text-neutral-900 dark:text-white">
                        Where are you headed?
                    </h1>
                    <p className="mt-1 text-neutral-500 dark:text-neutral-400">
                        Pick a route and a departure time, then choose your
                        seats.
                    </p>
                </header>

                <div className="mb-6 max-w-xs">
                    <Label htmlFor="date" className="mb-1.5 block">
                        Travel date
                    </Label>
                    <Input
                        id="date"
                        type="date"
                        min={tomorrow()}
                        value={date}
                        onChange={(e) => setDate(e.target.value)}
                    />
                </div>

                <div className="grid gap-4">
                    {corridors.length === 0 ? (
                        <p className="text-neutral-500">
                            No trips are available yet.
                        </p>
                    ) : (
                        corridors.map((corridor) => (
                            <TripCard
                                key={corridor.directions[0].route_id}
                                corridor={corridor}
                                date={date}
                            />
                        ))
                    )}
                </div>
            </div>
        </>
    );
}
