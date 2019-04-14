<?php

namespace KanbanBoard\Entities;


class Progress implements \JsonSerializable
{
    private $total;
    private $complete;
    private $remaining;
    private $percent = null;

    public function __construct(int $completed, int $remaining)
    {
        $this->complete = $completed;
        $this->remaining = $remaining;
        $this->fillTotal();
        $this->calculatePercent();
    }

    private function calculatePercent()
    {
        $this->percent = $this->total ? round($this->complete / $this->total * 100) : null;
    }

    private function fillTotal()
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

    public function jsonSerialize()
    {
        return [
            'total' => $this->total,
            'complete' => $this->complete,
            'remaining' => $this->remaining,
            'percent' => $this->percent
        ];
    }
}