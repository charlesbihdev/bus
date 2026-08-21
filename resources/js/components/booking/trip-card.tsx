import { Link } from '@inertiajs/react';
import { ArrowLeftRight, Bus, Clock } from 'lucide-react';
import { useState } from 'react';
import { cedis, duration, time12 } from '@/lib/format';
import { seats } from '@/routes/trips';
import { GlassCard } from './glass-card';

export interface CorridorDirection {
    route_id: number;
    origin: string;
    destination: string;
    duration_minutes: number | null;
    times: { schedule_id: number; departure_time: string; price: number }[];
}

export interface Corridor {
    directions: CorridorDirection[];
}

export function TripCard({ corridor, date }: { corridor: Corridor; date: string }) {
    const [dir, setDir] = useState(0);
    const current = corridor.directions[dir];
    const canSwap = corridor.directions.length > 1;
    const fromPrice = Math.min(...current.times.map((t) => t.price));

    return (
        <GlassCard className="overflow-hidden p-6">
            {/* Equal 3-col grid keeps the icon dead-centre regardless of town name length. */}
            <div className="grid grid-cols-3 items-center gap-2">
                <p key={`o-${dir}`} className="min-w-0 text-left text-lg font-bold break-words text-neutral-900 duration-300 animate-in fade-in slide-in-from-left-2 dark:text-white">
                    {current.origin}
                </p>

                <div className="flex flex-col items-center gap-1 text-neutral-400">
                    {canSwap ? (
                        <button
                            type="button"
                            onClick={() => setDir((d) => (d + 1) % corridor.directions.length)}
                            aria-label="Reverse direction"
                            title="Reverse direction"
                            style={{ transform: `rotate(${dir * 180}deg)` }}
                            className="flex size-8 items-center justify-center rounded-full border border-black/10 bg-white/70 text-brand-red transition-transform duration-300 ease-out hover:border-brand-yellow hover:bg-brand-yellow/15 dark:border-white/15 dark:bg-white/5"
                        >
                            <ArrowLeftRight className="size-4" />
                        </button>
                    ) : (
                        <Bus className="size-4 text-brand-red" />
                    )}
                    <div className="h-px w-full border-t border-dashed border-current" />
                </div>

                <p key={`d-${dir}`} className="min-w-0 text-right text-lg font-bold break-words text-neutral-900 duration-300 animate-in fade-in slide-in-from-right-2 dark:text-white">
                    {current.destination}
                </p>
            </div>

            <div key={dir} className="duration-300 animate-in fade-in">
                <div className="mt-4 flex items-center gap-4 text-sm text-neutral-500 dark:text-neutral-400">
                    {current.duration_minutes && (
                        <span className="flex items-center gap-1.5"><Clock className="size-4" /> {duration(current.duration_minutes)}</span>
                    )}
                    <span className="font-semibold text-neutral-900 dark:text-white">from {cedis(fromPrice)}</span>
                </div>

                <div className="mt-5">
                    <p className="mb-2 text-xs font-medium tracking-wide text-neutral-400 uppercase">Departures</p>
                    <div className="flex flex-wrap gap-2">
                        {current.times.map((t) => (
                            <Link
                                key={t.schedule_id}
                                href={seats(t.schedule_id, { query: { date } })}
                                className="rounded-full border border-black/10 bg-white/60 px-4 py-1.5 text-sm font-medium text-neutral-800 transition-colors hover:border-brand-yellow hover:bg-brand-yellow/15 dark:border-white/15 dark:bg-white/5 dark:text-neutral-200"
                            >
                                {time12(t.departure_time)}
                            </Link>
                        ))}
                    </div>
                </div>
            </div>
        </GlassCard>
    );
}
