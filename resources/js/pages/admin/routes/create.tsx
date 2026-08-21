import { Head } from '@inertiajs/react';
import { index, store } from '@/actions/App/Http/Controllers/Admin/RouteController';
import { RouteForm  } from '@/components/admin/route-form';
import type {TownOption} from '@/components/admin/route-form';

export default function CreateRoute({ towns }: { towns: TownOption[] }) {
    return (
        <>
            <Head title="New route" />
            <div className="p-4 md:p-8">
                <h1 className="mb-6 text-2xl font-bold tracking-tight">New route</h1>
                <RouteForm action={store.form()} towns={towns} submitLabel="Create route" />
            </div>
        </>
    );
}

CreateRoute.layout = {
    breadcrumbs: [
        { title: 'Routes', href: index() },
        { title: 'New', href: '#' },
    ],
};
