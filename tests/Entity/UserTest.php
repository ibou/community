<?php

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;


/**
 * ClassNameTest
 * @group group
 */
class UserTest extends TestCase
{
    /**
     *
     * @var \App\Entity\User
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new User();
    }

    /** @test */
    public function testId()
    {
        $this->assertNull($this->object->getId());
        $reflection = new \ReflectionClass($this->object);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($this->object, 4);
        $this->assertEquals(4, $this->object->getId());
    }

}