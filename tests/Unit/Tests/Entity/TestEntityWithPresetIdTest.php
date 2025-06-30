<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Unit\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineDirectInsertBundle\Tests\Entity\TestEntityWithPresetId;

class TestEntityWithPresetIdTest extends TestCase
{
    private TestEntityWithPresetId $entity;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entity = new TestEntityWithPresetId();
    }

    public function testIdIsNullByDefault(): void
    {
        $this->assertNull($this->entity->getId());
    }

    public function testSetAndGetId(): void
    {
        $id = 123;
        $this->entity->setId($id);
        $this->assertEquals($id, $this->entity->getId());
    }

    public function testSetIdWithNull(): void
    {
        $this->entity->setId(456);
        $this->entity->setId(null);
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
        $name = 'Entity with Preset ID';
        $this->entity->setName($name);
        $this->assertEquals($name, (string) $this->entity);
    }
}