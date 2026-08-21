import { Head } from '@inertiajs/react';
import { ArrowRight, Clock } from 'lucide-react';
import { useState } from 'react';
import { BookingSummary } from '@/components/booking/booking-summary';
import { GlassCard } from '@/components/booking/glass-card';
import { SeatMap } from '@/components/booking/seat-map';
import type { SeatMapData } from '@/components/booking/types';
import { cedis, duration, time12 } from '@/lib/format';

interface Trip {
    schedule_id: number;
    date: string;
    origin: string;
    destination: string;
    departure_time: string;
    duration_minutes: number | null;
    bus: string;
    price: number;
}

export default function Seats({
    trip,
    seatMap,
}: {
    trip: Trip;
    seatMap: SeatMapData;
}) {
    const [selected, setSelected] = useState<string[]>([]);

    function toggle(label: string) {
        setSelected((prev) =>
            prev.includes(label)
                ? prev.filter((s) => s !== label)
                : [...prev, label],
        );
    }

    return (
        <>
            <Head title={`${trip.origin} → ${trip.destination}`} />
            <div className="mx-auto w-full max-w-5xl p-4 md:p-8">
                <GlassCard className="mb-6 flex flex-wrap items-center justify-between gap-4 p-6">
                    <div className="flex items-center gap-3 text-xl font-bold text-neutral-900 dark:text-white">
                        {trip.origin}{' '}
                        <ArrowRight className="size-5 text-brand-red" />{' '}
                        {trip.destination}
                    </div>
                    <div className="flex items-center gap-5 text-sm text-neutral-500 dark:text-neutral-400">
                        <span>{trip.date}</span>
                        <span className="flex items-center gap-1.5">
                            <Clock className="size-4" />
                            {time12(trip.departure_time)}
                        </span>
                        {trip.duration_minutes && (
                            <span>{duration(trip.duration_minutes)}</span>
                        )}
                        <span className="font-semibold text-neutral-900 dark:text-white">
                            {cedis(trip.price)} / seat
                        </span>
                    </div>
                </GlassCard>

                <div className="grid gap-6 lg:grid-cols-[1fr_20rem]">
                    <GlassCard className="p-6">
                        <SeatMap
                            layout={seatMap.layout}
                            selected={selected}
                            onToggle={toggle}
                        />
                    </GlassCard>

                    <BookingSummary
                        scheduleId={trip.schedule_id}
                        date={trip.date}
                        unitPrice={trip.price}
                        selected={selected}
                    />
                </div>
            </div>
        </>
    );
}
