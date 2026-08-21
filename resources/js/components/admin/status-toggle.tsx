import { router } from '@inertiajs/react';
import { Switch } from '@/components/ui/switch';

/** A status switch that persists the change inline via an Inertia PATCH. */
export function StatusToggle({ active, url }: { active: boolean; url: string }) {
    return (
        <Switch
            checked={active}
            onCheckedChange={() => router.patch(url, {}, { preserveScroll: true })}
        />
    );
}
