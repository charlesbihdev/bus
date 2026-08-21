import { cn } from '@/lib/utils';
import type { SeatState } from './types';

const STYLES: Record<SeatState, string> = {
    available:
        'border-black/10 bg-white text-neutral-600 hover:border-brand-yellow hover:bg-brand-yellow/10 dark:border-white/15 dark:bg-white/5 dark:text-neutral-300',
    selected:
        'border-transparent bg-brand-yellow text-neutral-900 shadow-sm shadow-brand-yellow/40',
    booked: 'cursor-not-allowed border-transparent bg-neutral-200 text-neutral-400 dark:bg-neutral-700 dark:text-neutral-500',
};

export function Seat({
    label,
    state,
    onSelect,
}: {
    label: string;
    state: SeatState;
    onSelect?: (label: string) => void;
}) {
    const disabled = state === 'booked';

    return (
        <button
            type="button"
            disabled={disabled}
            aria-pressed={state === 'selected'}
            aria-label={`Seat ${label}, ${state}`}
            onClick={() => onSelect?.(label)}
            className={cn(
                'flex size-9 items-center justify-center rounded-lg border text-xs font-semibold transition-colors',
                'focus-visible:ring-2 focus-visible:ring-brand-yellow focus-visible:outline-none',
                STYLES[state],
            )}
        >
            {label}
        </button>
    );
}
