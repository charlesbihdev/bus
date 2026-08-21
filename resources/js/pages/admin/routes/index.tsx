import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftRight, Pencil, Plus, Trash2 } from 'lucide-react';
import { create, destroy, edit, index, toggle } from '@/actions/App/Http/Controllers/Admin/RouteController';
import { StatusToggle } from '@/components/admin/status-toggle';
import { Button } from '@/components/ui/button';

interface Corridor {
    id: number;
    origin: string;
    destination: string;
    departures: number;
    is_active: boolean;
}

export default function RoutesIndex({ corridors }: { corridors: Corridor[] }) {
    return (
        <>
            <Head title="Routes" />
            <div className="p-4 md:p-8">
                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-2xl font-bold tracking-tight">Routes</h1>
                    <Button asChild><Link href={create()}><Plus className="size-4" /> New route</Link></Button>
                </div>

                <div className="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left text-xs text-muted-foreground uppercase">
                            <tr>
                                <th className="px-4 py-3 font-medium">Corridor</th>
                                <th className="px-4 py-3 font-medium">Departures</th>
                                <th className="px-4 py-3 font-medium">Active</th>
                                <th className="px-4 py-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {corridors.map((c) => (
                                <tr key={c.id} className="border-t border-sidebar-border/70 dark:border-sidebar-border">
                                    <td className="px-4 py-3">
                                        <span className="flex items-center gap-2 font-medium">
                                            {c.origin} <ArrowLeftRight className="size-4 text-brand-red" /> {c.destination}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-muted-foreground">{c.departures}</td>
                                    <td className="px-4 py-3"><StatusToggle active={c.is_active} url={toggle.url(c.id)} /></td>
                                    <td className="px-4 py-3">
                                        <div className="flex items-center justify-end gap-1">
                                            <Button asChild variant="ghost" size="sm"><Link href={edit(c.id)}><Pencil className="size-4" /> Manage</Link></Button>
                                            <Form {...destroy.form(c.id)}>
                                                <Button type="submit" variant="ghost" size="sm" className="text-brand-red"><Trash2 className="size-4" /></Button>
                                            </Form>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {corridors.length === 0 && (
                                <tr><td colSpan={4} className="px-4 py-10 text-center text-muted-foreground">No routes yet.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

RoutesIndex.layout = {
    breadcrumbs: [{ title: 'Routes', href: index() }],
};
