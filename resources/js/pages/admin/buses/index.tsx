import { Form, Head, Link } from '@inertiajs/react';
import { Pencil, Plus, Trash2 } from 'lucide-react';
import { create, destroy, edit, index, toggle } from '@/actions/App/Http/Controllers/Admin/BusController';
import { StatusToggle } from '@/components/admin/status-toggle';
import { Button } from '@/components/ui/button';

interface BusRow {
    id: number;
    name: string;
    operator: string | null;
    seat_count: number;
    is_active: boolean;
}

export default function BusesIndex({ buses }: { buses: BusRow[] }) {
    return (
        <>
            <Head title="Buses" />
            <div className="p-4 md:p-8">
                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-2xl font-bold tracking-tight">Buses</h1>
                    <Button asChild><Link href={create()}><Plus className="size-4" /> New bus</Link></Button>
                </div>

                <div className="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left text-xs text-muted-foreground uppercase">
                            <tr>
                                <th className="px-4 py-3 font-medium">Name</th>
                                <th className="px-4 py-3 font-medium">Operator</th>
                                <th className="px-4 py-3 font-medium">Seats</th>
                                <th className="px-4 py-3 font-medium">Status</th>
                                <th className="px-4 py-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {buses.map((b) => (
                                <tr key={b.id} className="border-t border-sidebar-border/70 dark:border-sidebar-border">
                                    <td className="px-4 py-3 font-medium">{b.name}</td>
                                    <td className="px-4 py-3 text-muted-foreground">{b.operator ?? '—'}</td>
                                    <td className="px-4 py-3 text-muted-foreground">{b.seat_count}</td>
                                    <td className="px-4 py-3"><StatusToggle active={b.is_active} url={toggle.url(b.id)} /></td>
                                    <td className="px-4 py-3">
                                        <div className="flex items-center justify-end gap-1">
                                            <Button asChild variant="ghost" size="sm"><Link href={edit(b.id)}><Pencil className="size-4" /></Link></Button>
                                            <Form {...destroy.form(b.id)}>
                                                <Button type="submit" variant="ghost" size="sm" className="text-brand-red"><Trash2 className="size-4" /></Button>
                                            </Form>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {buses.length === 0 && (
                                <tr><td colSpan={5} className="px-4 py-10 text-center text-muted-foreground">No buses yet.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

BusesIndex.layout = {
    breadcrumbs: [{ title: 'Buses', href: index() }],
};
