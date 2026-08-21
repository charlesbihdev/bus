import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export interface TownDefaults {
    name: string;
    region: string | null;
    is_active: boolean;
}

export function TownForm({
    action,
    town,
    submitLabel,
}: {
    action: { action: string; method: 'post' | 'put' };
    town?: TownDefaults;
    submitLabel: string;
}) {
    const [active, setActive] = useState(town?.is_active ?? true);

    return (
        <Form {...action} className="max-w-lg space-y-5">
            {({ processing, errors }) => (
                <>
                    <div className="space-y-1.5">
                        <Label htmlFor="name">Town name</Label>
                        <Input id="name" name="name" defaultValue={town?.name} placeholder="Kumasi" />
                        {errors.name && <p className="text-sm text-brand-red">{errors.name}</p>}
                    </div>

                    <div className="space-y-1.5">
                        <Label htmlFor="region">Region</Label>
                        <Input id="region" name="region" defaultValue={town?.region ?? ''} placeholder="Ashanti" />
                        {errors.region && <p className="text-sm text-brand-red">{errors.region}</p>}
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
