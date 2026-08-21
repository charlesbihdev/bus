import { cn } from '@/lib/utils';

const ITEMS = [
    {
        label: 'Available',
        className:
            'border border-black/10 bg-white dark:border-white/15 dark:bg-white/5',
    },
    { label: 'Selected', className: 'bg-brand-yellow' },
    { label: 'Booked', className: 'bg-neutral-300 dark:bg-neutral-600' },
];

export function SeatLegend() {
    return (
        <div className="flex flex-wrap items-center gap-4 text-xs text-neutral-500 dark:text-neutral-400">
            {ITEMS.map((item) => (
                <span key={item.label} className="flex items-center gap-2">
                    <span className={cn('size-4 rounded-md', item.className)} />
                    {item.label}
                </span>
            ))}
        </div>
    );
}
