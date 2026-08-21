import { Head } from '@inertiajs/react';
import { index, store } from '@/actions/App/Http/Controllers/Admin/TownController';
import { TownForm } from '@/components/admin/town-form';

export default function CreateTown() {
    return (
        <>
            <Head title="New town" />
            <div className="p-4 md:p-8">
                <h1 className="mb-6 text-2xl font-bold tracking-tight">New town</h1>
                <TownForm action={store.form()} submitLabel="Create town" />
            </div>
        </>
    );
}

CreateTown.layout = {
    breadcrumbs: [
        { title: 'Towns', href: index() },
        { title: 'New', href: '#' },
    ],
};
