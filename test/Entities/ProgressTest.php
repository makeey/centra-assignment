<?php

use PHPUnit\Framework\TestCase;

class ProgressTest extends TestCase
{
    public function testCalculateWithZeroValues()
    {
        $progress = new \KanbanBoard\Entities\Progress(0, 0);
        $this->assertEquals($progress->total(), 0);
        $this->assertEquals($progress->remaining(), 0);
        $this->assertEquals($progress->complete(), 0);
        $this->assertEquals($progress->percent(), 0);
    }

    public function testCalculateWithValues()
    {
        $progress = new \KanbanBoard\Entities\Progress(5, 5);
        $this->assertEquals($progress->total(), 10);
        $this->assertEquals($progress->remaining(), 5);
        $this->assertEquals($progress->complete(), 5);
        $this->assertEqualsWithDelta($progress->percent(), 50, 0.0001);
    }

    public function testCalculateWithoutCompleteTasks()
    {
        $progress = new \KanbanBoard\Entities\Progress(0, 5);
        $this->assertEquals($progress->total(), 5);
        $this->assertEquals($progress->remaining(), 5);
        $this->assertEquals($progress->complete(), 0);
        $this->assertEqualsWithDelta($progress->percent(), 0, 0.0001);
    }

    public function testCalculateWithoutRemaining()
    {
        $progress = new \KanbanBoard\Entities\Progress(10, 0);
        $this->assertEquals($progress->total(), 10);
        $this->assertEquals($progress->remaining(), 0);
        $this->assertEquals($progress->complete(), 10);
        $this->assertEqualsWithDelta($progress->percent(), 100, 0.0001);
    }
}