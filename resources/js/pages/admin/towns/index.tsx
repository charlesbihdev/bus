import { Form, Head, Link } from '@inertiajs/react';
import { Pencil, Plus, Trash2 } from 'lucide-react';
import { create, destroy, edit, index, toggle } from '@/actions/App/Http/Controllers/Admin/TownController';
import { StatusToggle } from '@/components/admin/status-toggle';
import { Button } from '@/components/ui/button';

interface TownRow {
    id: number;
    name: string;
    region: string | null;
    is_active: boolean;
}

export default function TownsIndex({ towns }: { towns: TownRow[] }) {
    return (
        <>
            <Head title="Towns" />
            <div className="p-4 md:p-8">
                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-2xl font-bold tracking-tight">Towns</h1>
                    <Button asChild><Link href={create()}><Plus className="size-4" /> New town</Link></Button>
                </div>

                <div className="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left text-xs text-muted-foreground uppercase">
                            <tr>
                                <th className="px-4 py-3 font-medium">Name</th>
                                <th className="px-4 py-3 font-medium">Region</th>
                                <th className="px-4 py-3 font-medium">Status</th>
                                <th className="px-4 py-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {towns.map((t) => (
                                <tr key={t.id} className="border-t border-sidebar-border/70 dark:border-sidebar-border">
                                    <td className="px-4 py-3 font-medium">{t.name}</td>
                                    <td className="px-4 py-3 text-muted-foreground">{t.region ?? '—'}</td>
                                    <td className="px-4 py-3"><StatusToggle active={t.is_active} url={toggle.url(t.id)} /></td>
                                    <td className="px-4 py-3">
                                        <div className="flex items-center justify-end gap-1">
                                            <Button asChild variant="ghost" size="sm"><Link href={edit(t.id)}><Pencil className="size-4" /></Link></Button>
                                            <Form {...destroy.form(t.id)}>
                                                <Button type="submit" variant="ghost" size="sm" className="text-brand-red"><Trash2 className="size-4" /></Button>
                                            </Form>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {towns.length === 0 && (
                                <tr><td colSpan={4} className="px-4 py-10 text-center text-muted-foreground">No towns yet.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

TownsIndex.layout = {
    breadcrumbs: [{ title: 'Towns', href: index() }],
};
