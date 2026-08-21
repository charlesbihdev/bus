import { Head } from '@inertiajs/react';
import { index, update } from '@/actions/App/Http/Controllers/Admin/BusController';
import { BusForm  } from '@/components/admin/bus-form';
import type {BusDefaults} from '@/components/admin/bus-form';

export default function EditBus({ bus }: { bus: BusDefaults & { id: number } }) {
    return (
        <>
            <Head title="Edit bus" />
            <div className="p-4 md:p-8">
                <h1 className="mb-6 text-2xl font-bold tracking-tight">Edit bus</h1>
                <BusForm action={update.form(bus.id)} bus={bus} submitLabel="Save changes" />
            </div>
        </>
    );
}

EditBus.layout = {
    breadcrumbs: [
        { title: 'Buses', href: index() },
        { title: 'Edit', href: '#' },
    ],
};
