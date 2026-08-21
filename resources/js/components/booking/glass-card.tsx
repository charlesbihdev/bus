import { cn } from '@/lib/utils';

/**
 * Frosted, softly-elevated surface — the shared "glass" look across the
 * booking flow. Inspired by inertiajs.com's layered cards (structure only).
 */
export function GlassCard({
    className,
    children,
    ...props
}: React.ComponentProps<'div'>) {
    return (
        <div
            className={cn(
                'rounded-2xl border border-black/5 bg-white/70 shadow-sm backdrop-blur-xl',
                'dark:border-white/10 dark:bg-white/5',
                className,
            )}
            {...props}
        >
            {children}
        </div>
    );
}
