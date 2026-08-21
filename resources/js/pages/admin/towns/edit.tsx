import { Head } from '@inertiajs/react';
import { index, update } from '@/actions/App/Http/Controllers/Admin/TownController';
import { TownForm  } from '@/components/admin/town-form';
import type {TownDefaults} from '@/components/admin/town-form';

export default function EditTown({ town }: { town: TownDefaults & { id: number } }) {
    return (
        <>
            <Head title="Edit town" />
            <div className="p-4 md:p-8">
                <h1 className="mb-6 text-2xl font-bold tracking-tight">Edit town</h1>
                <TownForm action={update.form(town.id)} town={town} submitLabel="Save changes" />
            </div>
        </>
    );
}

EditTown.layout = {
    breadcrumbs: [
        { title: 'Towns', href: index() },
        { title: 'Edit', href: '#' },
    ],
};
