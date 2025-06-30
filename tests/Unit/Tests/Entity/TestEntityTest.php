<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Unit\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineDirectInsertBundle\Tests\Entity\TestEntity;

class TestEntityTest extends TestCase
{
    private TestEntity $entity;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entity = new TestEntity();
    }

    public function testIdIsNullByDefault(): void
    {
        $this->assertNull($this->entity->getId());
    }

    public function testSetAndGetName(): void
    {
        $name = 'Test Name';
        $this->entity->setName($name);
        $this->assertEquals($name, $this->entity->getName());
    }

    public function testToString(): void
    {
        $name = 'Test Entity Name';
        $this->entity->setName($name);
        $this->assertEquals($name, (string) $this->entity);
    }
}