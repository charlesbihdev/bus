import { Form, router } from '@inertiajs/react';
import { ArrowRight, CalendarDays, MoreVertical, Trash2 } from 'lucide-react';
import { index as dates } from '@/actions/App/Http/Controllers/Admin/DepartureController';
import { destroy, store, toggle } from '@/actions/App/Http/Controllers/Admin/ScheduleController';
import { StatusToggle } from '@/components/admin/status-toggle';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { cedis, time12 } from '@/lib/format';

export interface Departure {
    id: number;
    departure_time: string;
    price: number;
    custom_price: boolean;
    bus: string;
    is_active: boolean;
}

export interface Direction {
    key: 'forward' | 'return';
    origin: string;
    destination: string;
    departures: Departure[];
}

const SELECT = 'h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm shadow-xs';

export function DirectionPanel({
    routeId,
    direction,
    buses,
}: {
    routeId: number;
    direction: Direction;
    buses: { id: number; name: string }[];
}) {
    return (
        <div className="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <div className="flex items-center gap-2 border-b border-sidebar-border/70 px-4 py-3 font-semibold dark:border-sidebar-border">
                {direction.origin} <ArrowRight className="size-4 text-brand-red" /> {direction.destination}
            </div>

            <div className="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                {direction.departures.map((d) => (
                    <div key={d.id} className="flex items-center gap-3 px-4 py-2.5 text-sm">
                        <span className="w-16 font-medium">{time12(d.departure_time)}</span>
                        <span className="w-20">
                            {cedis(d.price)}
                            {d.custom_price && <span className="ml-0.5 text-xs text-brand-red">•</span>}
                        </span>
                        <span className="min-w-0 flex-1 truncate text-muted-foreground">{d.bus}</span>
                        <StatusToggle active={d.is_active} url={toggle.url(d.id)} />
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="ghost" size="icon" className="size-8"><MoreVertical className="size-4" /></Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem onSelect={() => router.visit(dates.url(d.id))}>
                                    <CalendarDays className="size-4" /> Departure dates
                                </DropdownMenuItem>
                                <DropdownMenuItem
                                    variant="destructive"
                                    onSelect={() => router.delete(destroy.url(d.id), { preserveScroll: true })}
                                >
                                    <Trash2 className="size-4" /> Delete
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                ))}
                {direction.departures.length === 0 && (
                    <p className="px-4 py-3 text-sm text-muted-foreground">No departures {direction.key === 'return' ? '— add one to offer the return trip' : 'yet'}.</p>
                )}
            </div>

            <Form {...store.form(routeId)} options={{ preserveScroll: true }} resetOnSuccess className="border-t border-sidebar-border/70 p-3 dark:border-sidebar-border">
                {({ processing, errors }) => (
                    <>
                        <div className="flex flex-wrap items-end gap-2">
                            <input type="hidden" name="direction" value={direction.key} />
                            <select name="bus_id" className={`${SELECT} w-32`} defaultValue={buses[0]?.id}>
                                {buses.map((b) => <option key={b.id} value={b.id}>{b.name}</option>)}
                            </select>
                            <Input name="departure_time" type="time" defaultValue="06:00" className="w-32" />
                            <Input name="price" type="number" step="0.01" min="0" placeholder="Route price" className="w-32" />
                            <Button type="submit" size="sm" disabled={processing}>Add departure</Button>
                        </div>
                        {errors.departure_time && <p className="mt-2 text-sm text-brand-red">{errors.departure_time}</p>}
                    </>
                )}
            </Form>
        </div>
    );
}
