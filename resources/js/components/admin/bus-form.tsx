import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export interface BusDefaults {
    name: string;
    operator: string | null;
    is_active: boolean;
}

export function BusForm({
    action,
    bus,
    submitLabel,
}: {
    action: { action: string; method: 'post' | 'put' };
    bus?: BusDefaults;
    submitLabel: string;
}) {
    const [active, setActive] = useState(bus?.is_active ?? true);

    return (
        <Form {...action} className="max-w-lg space-y-5">
            {({ processing, errors }) => (
                <>
                    <div className="space-y-1.5">
                        <Label htmlFor="name">Bus name</Label>
                        <Input id="name" name="name" defaultValue={bus?.name} placeholder="VIP-02" />
                        {errors.name && <p className="text-sm text-brand-red">{errors.name}</p>}
                    </div>

                    <div className="space-y-1.5">
                        <Label htmlFor="operator">Operator</Label>
                        <Input id="operator" name="operator" defaultValue={bus?.operator ?? ''} placeholder="BookBus VIP" />
                        {errors.operator && <p className="text-sm text-brand-red">{errors.operator}</p>}
                    </div>

                    <p className="rounded-lg bg-muted/50 px-3 py-2 text-xs text-muted-foreground">
                        New buses use the standard 45-seat 2+2 layout. Custom layouts come later.
                    </p>

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
