<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TestEntityWithAllTypesTest extends TestCase
{
    public function testEntityCreation(): void
    {
        // Arrange & Act
        $entity = new TestEntityWithAllTypes();

        // Assert
        $this->assertInstanceOf(TestEntityWithAllTypes::class, $entity);
        $this->assertInstanceOf(\Stringable::class, $entity);
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getDatetimeType());
        $this->assertNull($entity->getNullableString());
    }

    public function testStringType(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        $value = 'test string';

        // Act
        $entity->setStringType($value);

        // Assert
        $this->assertEquals($value, $entity->getStringType());
    }

    public function testTextType(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        $value = 'test text content';

        // Act
        $entity->setTextType($value);

        // Assert
        $this->assertEquals($value, $entity->getTextType());
    }

    public function testIntegerType(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        $value = 123;

        // Act
        $entity->setIntegerType($value);

        // Assert
        $this->assertEquals($value, $entity->getIntegerType());
    }

    public function testFloatType(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        $value = 123.45;

        // Act
        $entity->setFloatType($value);

        // Assert
        $this->assertEquals($value, $entity->getFloatType());
    }

    public function testBooleanType(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();

        // Act & Assert - true
        $entity->setBooleanType(true);
        $this->assertTrue($entity->getBooleanType());

        // Act & Assert - false
        $entity->setBooleanType(false);
        $this->assertFalse($entity->getBooleanType());
    }

    public function testDatetimeType(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        $datetime = new DateTimeImmutable('2023-12-01 12:00:00');

        // Act
        $entity->setDatetimeType($datetime);

        // Assert
        $this->assertEquals($datetime, $entity->getDatetimeType());

        // Test null
        $entity->setDatetimeType(null);
        $this->assertNull($entity->getDatetimeType());
    }

    public function testNullableString(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        $value = 'nullable string';

        // Act & Assert - with value
        $entity->setNullableString($value);
        $this->assertEquals($value, $entity->getNullableString());

        // Act & Assert - with null
        $entity->setNullableString(null);
        $this->assertNull($entity->getNullableString());
    }

    public function testToStringWithValue(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        $stringValue = 'test string';
        $entity->setStringType($stringValue);

        // Act
        $result = (string) $entity;

        // Assert
        $this->assertEquals($stringValue, $result);
    }

    public function testToStringWithoutStringType(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        // 不设置 stringType，使用反射设置 id
        $reflection = new \ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, 123);

        // Act
        $result = (string) $entity;

        // Assert
        $this->assertEquals('TestEntityWithAllTypes#123', $result);
    }
} 