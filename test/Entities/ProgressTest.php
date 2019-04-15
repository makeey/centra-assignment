<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use KanbanBoard\Entities\Progress;
use PHPUnit\Framework\TestCase;

class ProgressTest extends TestCase
{
    public function testCalculateWithZeroValues()
    {
        $progress = new Progress(0, 0);
        $this->assertEquals($progress->total(), 0);
        $this->assertEquals($progress->remaining(), 0);
        $this->assertEquals($progress->complete(), 0);
        $this->assertNull($progress->percent());
    }

    public function testCalculateWithValues()
    {
        $progress = new Progress(5, 5);
        $this->assertEquals($progress->total(), 10);
        $this->assertEquals($progress->remaining(), 5);
        $this->assertEquals($progress->complete(), 5);
        $this->assertEqualsWithDelta($progress->percent(), 50, 0.0001);
    }

    public function testCalculateWithoutCompleteTasks()
    {
        $progress = new Progress(0, 5);
        $this->assertEquals($progress->total(), 5);
        $this->assertEquals($progress->remaining(), 5);
        $this->assertEquals($progress->complete(), 0);
        $this->assertEqualsWithDelta($progress->percent(), 0, 0.0001);
    }

    public function testCalculateWithoutRemaining()
    {
        $progress = new Progress(10, 0);
        $this->assertEquals($progress->total(), 10);
        $this->assertEquals($progress->remaining(), 0);
        $this->assertEquals($progress->complete(), 10);
        $this->assertEqualsWithDelta($progress->percent(), 100, 0.0001);
    }
}
