import { Head } from '@inertiajs/react';
import { index, store } from '@/actions/App/Http/Controllers/Admin/BusController';
import { BusForm } from '@/components/admin/bus-form';

export default function CreateBus() {
    return (
        <>
            <Head title="New bus" />
            <div className="p-4 md:p-8">
                <h1 className="mb-6 text-2xl font-bold tracking-tight">New bus</h1>
                <BusForm action={store.form()} submitLabel="Create bus" />
            </div>
        </>
    );
}

CreateBus.layout = {
    breadcrumbs: [
        { title: 'Buses', href: index() },
        { title: 'New', href: '#' },
    ],
};
