import { Seat } from './seat';
import { SeatLegend } from './seat-legend';
import type { SeatRow } from './types';

function SteeringWheel() {
    return (
        <svg
            viewBox="0 0 24 24"
            className="size-7 text-neutral-700 dark:text-neutral-300"
            fill="none"
        >
            <circle
                cx="12"
                cy="12"
                r="9"
                stroke="currentColor"
                strokeWidth="1.6"
            />
            <circle cx="12" cy="12" r="2.4" fill="currentColor" />
            <path
                d="M12 14.4V21M9.7 12.9 4.2 16.1M14.3 12.9l5.5 3.2"
                stroke="currentColor"
                strokeWidth="1.6"
            />
        </svg>
    );
}

export function SeatMap({
    layout,
    selected,
    onToggle,
}: {
    layout: SeatRow[];
    selected: string[];
    onToggle: (label: string) => void;
}) {
    return (
        <div className="flex flex-col gap-5">
            <SeatLegend />

            <div className="mx-auto w-full max-w-xs rounded-2xl border border-black/5 bg-neutral-50/60 p-5 dark:border-white/10 dark:bg-white/5">
                <div className="mb-4 flex justify-end">
                    <SteeringWheel />
                </div>

                <div className="grid grid-cols-5 gap-2">
                    {layout
                        .flat()
                        .map((cell, i) =>
                            cell === null ? (
                                <span
                                    key={`gap-${i}`}
                                    aria-hidden
                                    className="size-9"
                                />
                            ) : (
                                <Seat
                                    key={cell.label}
                                    label={cell.label}
                                    state={
                                        cell.state === 'booked'
                                            ? 'booked'
                                            : selected.includes(cell.label)
                                              ? 'selected'
                                              : 'available'
                                    }
                                    onSelect={onToggle}
                                />
                            ),
                        )}
                </div>
            </div>
        </div>
    );
}
