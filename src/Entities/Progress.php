<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Entities;

class Progress implements \JsonSerializable
{
    /** @var int */
    private $total = 0;
    /** @var int  */
    private $complete;
    /** @var int  */
    private $remaining;
    /** @var ?float */
    private $percent = null;

    public function __construct(int $completed, int $remaining)
    {
        $this->complete = $completed;
        $this->remaining = $remaining;
        $this->fillTotal();
        $this->calculatePercent();
    }

    private function calculatePercent(): void
    {
        $this->percent = $this->total !== 0 ? \round($this->complete / $this->total * 100) : null;
    }

    private function fillTotal(): void
    {
        $this->total = $this->complete + $this->remaining;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function complete(): int
    {
        return $this->complete;
    }

    public function remaining(): int
    {
        return $this->remaining;
    }

    public function percent(): ?float
    {
        return $this->percent;
    }

    public function jsonSerialize(): array
    {
        return [
            'total' => $this->total,
            'complete' => $this->complete,
            'remaining' => $this->remaining,
            'percent' => $this->percent,
        ];
    }
}
