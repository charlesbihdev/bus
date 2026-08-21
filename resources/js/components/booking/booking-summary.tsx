import { Form, Link, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cedis } from '@/lib/format';
import { login } from '@/routes';
import { store } from '@/routes/bookings';
import type { Auth } from '@/types';
import { GlassCard } from './glass-card';

export function BookingSummary({
    scheduleId,
    date,
    unitPrice,
    selected,
}: {
    scheduleId: number;
    date: string;
    unitPrice: number;
    selected: string[];
}) {
    // const user = usePage<{ auth: Auth }>().props.auth?.user;
    const total = unitPrice * selected.length;

    return (
        <GlassCard className="p-6">
            <h2 className="text-base font-semibold text-neutral-900 dark:text-white">
                Your selection
            </h2>

            <div className="mt-3 min-h-9">
                {selected.length === 0 ? (
                    <p className="text-sm text-neutral-400">
                        Pick your seats from the map.
                    </p>
                ) : (
                    <div className="flex flex-wrap gap-2">
                        {selected.map((s) => (
                            <span
                                key={s}
                                className="rounded-md bg-brand-yellow px-2.5 py-1 text-xs font-semibold text-neutral-900"
                            >
                                Seat {s}
                            </span>
                        ))}
                    </div>
                )}
            </div>

            <Form
                {...store.form()}
                options={{ preserveScroll: true }}
                className="mt-5 space-y-4"
            >
                {({ processing, errors }) => (
                    <>
                        <input
                            type="hidden"
                            name="schedule_id"
                            value={scheduleId}
                        />
                        <input type="hidden" name="date" value={date} />
                        {selected.map((s) => (
                            <input
                                key={s}
                                type="hidden"
                                name="seats[]"
                                value={s}
                            />
                        ))}

                        <div className="space-y-1.5">
                            <Label htmlFor="contact_name">Full name</Label>
                            <Input
                                id="contact_name"
                                name="contact_name"
                                placeholder="Kwame Mensah"
                            />
                        </div>
                        <div className="space-y-1.5">
                            <Label htmlFor="contact_phone">Phone</Label>
                            <Input
                                id="contact_phone"
                                name="contact_phone"
                                placeholder="024 000 0000"
                            />
                        </div>

                        {errors.seats && (
                            <p className="text-sm text-brand-red">
                                {errors.seats}
                            </p>
                        )}

                        <div className="flex items-center justify-between border-t border-black/5 pt-4 dark:border-white/10">
                            <span className="text-sm text-neutral-500">
                                Total
                            </span>
                            <span className="text-lg font-bold text-neutral-900 dark:text-white">
                                {cedis(total)}
                            </span>
                        </div>

                        <Button
                            type="submit"
                            disabled={selected.length === 0 || processing}
                            className="w-full rounded-full bg-brand-red py-6 text-base font-semibold text-white shadow-lg shadow-brand-red/25 hover:bg-brand-red/90"
                        >
                            Continue to payment
                        </Button>
                    </>
                )}
            </Form>
        </GlassCard>
    );
}
