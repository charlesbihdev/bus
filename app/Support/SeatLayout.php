<?php

namespace App\Support;

class SeatLayout
{
    /**
     * Standard 45-seat VIP coach: 2 + [aisle] + 2 for ten rows,
     * then a full-width 5-seat back row. `null` marks the aisle gap.
     *
     * @return array<int, array<int, string|null>>
     */
    public static function vip45(): array
    {
        $rows = [];
        $seat = 1;

        for ($r = 0; $r < 10; $r++) {
            $rows[] = [
                (string) $seat,
                (string) ($seat + 1),
                null,
                (string) ($seat + 2),
                (string) ($seat + 3),
            ];
            $seat += 4;
        }

        // Back row: 41 42 43 44 45
        $rows[] = array_map(fn (int $n) => (string) $n, range($seat, $seat + 4));

        return $rows;
    }

    /**
     * Flat list of every seat label in a layout (aisles excluded).
     *
     * @param  array<int, array<int, string|null>>  $layout
     * @return array<int, string>
     */
    public static function labels(array $layout): array
    {
        return array_values(array_filter(
            array_merge(...$layout),
            fn (?string $cell) => $cell !== null,
        ));
    }
}
