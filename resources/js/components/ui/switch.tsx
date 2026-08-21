import { cn } from '@/lib/utils';

export function Switch({
    checked,
    onCheckedChange,
    disabled,
    className,
    ...props
}: {
    checked: boolean;
    onCheckedChange?: (value: boolean) => void;
    disabled?: boolean;
} & Omit<React.ComponentProps<'button'>, 'onChange'>) {
    return (
        <button
            type="button"
            role="switch"
            aria-checked={checked}
            disabled={disabled}
            onClick={() => onCheckedChange?.(!checked)}
            className={cn(
                'inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full transition-colors outline-none focus-visible:ring-2 focus-visible:ring-brand-yellow disabled:cursor-not-allowed disabled:opacity-50',
                checked ? 'bg-emerald-500' : 'bg-neutral-300 dark:bg-neutral-600',
                className,
            )}
            {...props}
        >
            <span
                className={cn(
                    'size-4 rounded-full bg-white shadow transition-transform',
                    checked ? 'translate-x-[18px]' : 'translate-x-0.5',
                )}
            />
        </button>
    );
}
