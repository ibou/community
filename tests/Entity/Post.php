<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use App\Entity\Post;

class PostTest extends TestCase
{
    /**
     * @var \App\Entity\Post
     */
    protected $object;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var \DateTime
     */
    protected $tomorrow;

    /**
     * @var \DateTime
     */
    protected $future;

    protected function setUp()
    {
        $this->object = new Post();
        $this->date = new \DateTime();
        $this->tomorrow = new \DateTime('tomorrow');
        $this->future = new \DateTime('5 day');
    }

    public function testId()
    {
        $this->assertNull($this->object->getId());
        $reflection = new \ReflectionClass($this->object);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($this->object, 9);
        $this->assertEquals(9, $this->object->getId());
    }
}
