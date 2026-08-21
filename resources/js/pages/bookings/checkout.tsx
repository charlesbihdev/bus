import { Head, Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { useEffect, useState } from 'react';
import { GlassCard } from '@/components/booking/glass-card';
import { Button } from '@/components/ui/button';
import { cedis, time12 } from '@/lib/format';
import { home } from '@/routes';

interface Booking {
    reference: string;
    status: string;
    origin: string;
    destination: string;
    date: string;
    departure_time: string;
    seats: string[];
    contact_name: string;
    contact_phone: string;
    total_amount: number;
    expires_at: string | null;
}

function useCountdown(iso: string | null): string {
    const [left, setLeft] = useState('');
    useEffect(() => {
        if (!iso) {
            return;
        }

        const tick = () => {
            const ms = new Date(iso).getTime() - Date.now();

            if (ms <= 0) {
                return setLeft('expired');
            }

            const m = Math.floor(ms / 60000);
            const s = Math.floor((ms % 60000) / 1000);
            setLeft(`${m}:${s.toString().padStart(2, '0')}`);
        };
        tick();
        const id = setInterval(tick, 1000);

        return () => clearInterval(id);
    }, [iso]);

    return left;
}

export default function Checkout({ booking }: { booking: Booking }) {
    const countdown = useCountdown(booking.expires_at);

    return (
        <>
            <Head title={`Checkout ${booking.reference}`} />
            <div className="mx-auto w-full max-w-lg p-4 md:p-8">
                <GlassCard className="p-6">
                    <div className="flex items-center justify-between">
                        <span className="text-xs font-medium tracking-wide text-neutral-400 uppercase">
                            Booking {booking.reference}
                        </span>
                        {countdown && countdown !== 'expired' && (
                            <span className="rounded-full bg-brand-yellow/20 px-3 py-1 text-xs font-semibold text-neutral-700 dark:text-brand-yellow">
                                Held {countdown}
                            </span>
                        )}
                    </div>

                    <div className="mt-4 flex items-center gap-3 text-2xl font-bold text-neutral-900 dark:text-white">
                        {booking.origin}{' '}
                        <ArrowRight className="size-5 text-brand-red" />{' '}
                        {booking.destination}
                    </div>
                    <p className="mt-1 text-sm text-neutral-500">
                        {booking.date} · {time12(booking.departure_time)}
                    </p>

                    <dl className="mt-6 space-y-3 border-t border-black/5 pt-4 text-sm dark:border-white/10">
                        <Row label="Passenger">{booking.contact_name}</Row>
                        <Row label="Phone">{booking.contact_phone}</Row>
                        <Row label="Seats">{booking.seats.join(', ')}</Row>
                        <Row label="Total">
                            <span className="text-base font-bold text-neutral-900 dark:text-white">
                                {cedis(booking.total_amount)}
                            </span>
                        </Row>
                    </dl>

                    <Button
                        disabled
                        className="mt-6 w-full rounded-full bg-brand-red py-6 text-base font-semibold text-white shadow-lg shadow-brand-red/25 hover:bg-brand-red/90"
                    >
                        Pay with Paystack
                    </Button>
                    <p className="mt-2 text-center text-xs text-neutral-400">
                        Payment is wired up in the next step.
                    </p>
                </GlassCard>

                <Link
                    href={home()}
                    className="mt-4 block text-center text-sm text-neutral-500 hover:underline"
                >
                    Back to trips
                </Link>
            </div>
        </>
    );
}

function Row({
    label,
    children,
}: {
    label: string;
    children: React.ReactNode;
}) {
    return (
        <div className="flex items-center justify-between">
            <dt className="text-neutral-500">{label}</dt>
            <dd className="text-neutral-800 dark:text-neutral-200">
                {children}
            </dd>
        </div>
    );
}
