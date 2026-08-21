import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export interface TownOption {
    id: number;
    label: string;
}

export interface RouteDefaults {
    origin_town_id: number;
    destination_town_id: number;
    base_price: number; // GHS
    duration_minutes: number | null;
    is_active: boolean;
}

const SELECT = 'h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm shadow-xs';

export function RouteForm({
    action,
    towns,
    route,
    submitLabel,
}: {
    action: { action: string; method: 'post' | 'put' };
    towns: TownOption[];
    route?: RouteDefaults;
    submitLabel: string;
}) {
    const [active, setActive] = useState(route?.is_active ?? true);

    return (
        <Form {...action} className="max-w-lg space-y-5">
            {({ processing, errors }) => (
                <>
                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-1.5">
                            <Label htmlFor="origin_town_id">From</Label>
                            <select id="origin_town_id" name="origin_town_id" defaultValue={route?.origin_town_id} className={SELECT}>
                                {towns.map((t) => <option key={t.id} value={t.id}>{t.label}</option>)}
                            </select>
                        </div>
                        <div className="space-y-1.5">
                            <Label htmlFor="destination_town_id">To</Label>
                            <select id="destination_town_id" name="destination_town_id" defaultValue={route?.destination_town_id} className={SELECT}>
                                {towns.map((t) => <option key={t.id} value={t.id}>{t.label}</option>)}
                            </select>
                        </div>
                    </div>
                    {errors.destination_town_id && <p className="text-sm text-brand-red">{errors.destination_town_id}</p>}

                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-1.5">
                            <Label htmlFor="base_price">Price (GHS)</Label>
                            <Input id="base_price" name="base_price" type="number" step="0.01" min="0" defaultValue={route?.base_price} placeholder="120.00" />
                            {errors.base_price && <p className="text-sm text-brand-red">{errors.base_price}</p>}
                        </div>
                        <div className="space-y-1.5">
                            <Label htmlFor="duration_minutes">Duration (mins)</Label>
                            <Input id="duration_minutes" name="duration_minutes" type="number" min="0" defaultValue={route?.duration_minutes ?? ''} placeholder="300" />
                        </div>
                    </div>

                    <label className="flex items-center gap-2 text-sm">
                        <input type="hidden" name="is_active" value={active ? 1 : 0} />
                        <Checkbox checked={active} onCheckedChange={(v) => setActive(v === true)} />
                        Active
                    </label>

                    <Button type="submit" disabled={processing}>{submitLabel}</Button>
                </>
            )}
        </Form>
    );
}
