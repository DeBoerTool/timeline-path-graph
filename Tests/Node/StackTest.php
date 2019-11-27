<?php

namespace Dbt\Timeline\Tests\Node;

use Dbt\Timeline\Node;
use Dbt\Timeline\Node\Stack;
use Dbt\Timeline\Tests\UnitTestCase;

class StackTest extends UnitTestCase
{
    /** @test */
    public function getting_a_stack_of_slices (): void
    {
        $start = $this->ts();
        $end = $this->ts(1);

        $nodes = new Stack(
            Node::anonymous($start),
            Node::anonymous($end)
        );

        $slices = $nodes->slices();

        $this->assertSame(1, $slices->count());

        $this->assertTrue(
            $start->equalTo($slices->get(0)->start())
        );

        $this->assertTrue(
            $end->equalTo($slices->get(0)->end())
        );
    }
}
