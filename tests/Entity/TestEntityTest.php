<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;

class TestEntityTest extends TestCase
{
    public function testEntityCreation(): void
    {
        // Arrange & Act
        $entity = new TestEntity();

        // Assert
        $this->assertInstanceOf(TestEntity::class, $entity);
        $this->assertInstanceOf(\Stringable::class, $entity);
        $this->assertNull($entity->getId());
    }

    public function testGetSetName(): void
    {
        // Arrange
        $entity = new TestEntity();
        $name = 'test name';

        // Act
        $entity->setName($name);

        // Assert
        $this->assertEquals($name, $entity->getName());
    }

    public function testToString(): void
    {
        // Arrange
        $entity = new TestEntity();
        $name = 'test entity name';
        $entity->setName($name);

        // Act
        $result = (string) $entity;

        // Assert
        $this->assertEquals($name, $result);
    }
} 