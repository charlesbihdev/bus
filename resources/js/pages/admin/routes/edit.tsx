import { Form, Head } from '@inertiajs/react';
import { ArrowLeftRight } from 'lucide-react';
import { index, update } from '@/actions/App/Http/Controllers/Admin/RouteController';
import { DirectionPanel  } from '@/components/admin/direction-panel';
import type {Direction} from '@/components/admin/direction-panel';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface RouteData {
    id: number;
    origin: string;
    destination: string;
    origin_town_id: number;
    destination_town_id: number;
    base_price: number;
    duration_minutes: number | null;
    is_active: boolean;
}

export default function EditRoute({
    route,
    buses,
    directions,
}: {
    route: RouteData;
    buses: { id: number; name: string }[];
    directions: Direction[];
}) {
    return (
        <>
            <Head title={`${route.origin} ⇄ ${route.destination}`} />
            <div className="mx-auto max-w-4xl p-4 md:p-8">
                <h1 className="mb-6 flex items-center gap-2 text-2xl font-bold tracking-tight">
                    {route.origin} <ArrowLeftRight className="size-5 text-brand-red" /> {route.destination}
                </h1>

                <Form {...update.form(route.id)} options={{ preserveScroll: true }} className="mb-8 flex flex-wrap items-end gap-4 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    {({ processing, errors }) => (
                        <>
                            <input type="hidden" name="origin_town_id" value={route.origin_town_id} />
                            <input type="hidden" name="destination_town_id" value={route.destination_town_id} />
                            <input type="hidden" name="is_active" value={route.is_active ? 1 : 0} />
                            <div className="space-y-1.5">
                                <Label htmlFor="base_price">Default price (GHS)</Label>
                                <Input id="base_price" name="base_price" type="number" step="0.01" min="0" defaultValue={route.base_price} className="w-40" />
                                {errors.base_price && <p className="text-sm text-brand-red">{errors.base_price}</p>}
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="duration_minutes">Duration (mins)</Label>
                                <Input id="duration_minutes" name="duration_minutes" type="number" min="0" defaultValue={route.duration_minutes ?? ''} className="w-40" />
                            </div>
                            <Button type="submit" variant="outline" disabled={processing}>Save settings</Button>
                        </>
                    )}
                </Form>

                <p className="mb-3 text-sm text-muted-foreground">
                    Add departure times for each direction. Leave a price blank to use the default price above.
                </p>

                <div className="grid gap-6 lg:grid-cols-2">
                    {directions.map((d) => (
                        <DirectionPanel key={d.key} routeId={route.id} direction={d} buses={buses} />
                    ))}
                </div>
            </div>
        </>
    );
}

EditRoute.layout = {
    breadcrumbs: [
        { title: 'Routes', href: index() },
        { title: 'Manage', href: '#' },
    ],
};
