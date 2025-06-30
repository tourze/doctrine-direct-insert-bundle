<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;

class TestEntityWithPresetIdTest extends TestCase
{
    public function testEntityCreation(): void
    {
        // Arrange & Act
        $entity = new TestEntityWithPresetId();

        // Assert
        $this->assertInstanceOf(TestEntityWithPresetId::class, $entity);
        $this->assertInstanceOf(\Stringable::class, $entity);
        $this->assertNull($entity->getId());
    }

    public function testGetSetId(): void
    {
        // Arrange
        $entity = new TestEntityWithPresetId();
        $id = 12345;

        // Act
        $entity->setId($id);

        // Assert
        $this->assertEquals($id, $entity->getId());

        // Test setting null
        $entity->setId(null);
        $this->assertNull($entity->getId());
    }

    public function testGetSetName(): void
    {
        // Arrange
        $entity = new TestEntityWithPresetId();
        $name = 'test name';

        // Act
        $entity->setName($name);

        // Assert
        $this->assertEquals($name, $entity->getName());
    }

    public function testToString(): void
    {
        // Arrange
        $entity = new TestEntityWithPresetId();
        $name = 'preset id entity';
        $entity->setName($name);

        // Act
        $result = (string) $entity;

        // Assert
        $this->assertEquals($name, $result);
    }
} 