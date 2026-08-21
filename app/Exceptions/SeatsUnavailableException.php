<?php

namespace App\Exceptions;

use RuntimeException;

class SeatsUnavailableException extends RuntimeException
{
    /** @param array<int, string> $seats */
    public function __construct(public readonly array $seats)
    {
        parent::__construct('Seats no longer available: '.implode(', ', $seats));
    }
}
