export type SeatState = 'available' | 'booked' | 'selected';

export interface SeatCell {
    label: string;
    state: 'available' | 'booked';
}

/** A layout row: each cell is a seat, or null for the aisle gap. */
export type SeatRow = (SeatCell | null)[];

export interface SeatMapData {
    layout: SeatRow[];
    taken: string[];
}
