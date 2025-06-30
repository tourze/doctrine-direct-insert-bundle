<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Unit\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineDirectInsertBundle\Tests\Entity\TestEntityWithAllTypes;

class TestEntityWithAllTypesTest extends TestCase
{
    private TestEntityWithAllTypes $entity;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entity = new TestEntityWithAllTypes();
    }

    public function testIdIsNullByDefault(): void
    {
        $this->assertNull($this->entity->getId());
    }

    public function testSetAndGetStringType(): void
    {
        $value = 'test string';
        $this->entity->setStringType($value);
        $this->assertEquals($value, $this->entity->getStringType());
    }

    public function testSetAndGetTextType(): void
    {
        $value = 'This is a longer text content';
        $this->entity->setTextType($value);
        $this->assertEquals($value, $this->entity->getTextType());
    }

    public function testSetAndGetIntegerType(): void
    {
        $value = 42;
        $this->entity->setIntegerType($value);
        $this->assertEquals($value, $this->entity->getIntegerType());
    }

    public function testSetAndGetFloatType(): void
    {
        $value = 3.14;
        $this->entity->setFloatType($value);
        $this->assertEquals($value, $this->entity->getFloatType());
    }

    public function testSetAndGetBooleanType(): void
    {
        $this->entity->setBooleanType(true);
        $this->assertTrue($this->entity->getBooleanType());
        
        $this->entity->setBooleanType(false);
        $this->assertFalse($this->entity->getBooleanType());
    }

    public function testSetAndGetDatetimeType(): void
    {
        $date = new DateTimeImmutable('2025-06-28 10:00:00');
        $this->entity->setDatetimeType($date);
        $this->assertSame($date, $this->entity->getDatetimeType());
        
        $this->entity->setDatetimeType(null);
        $this->assertNull($this->entity->getDatetimeType());
    }

    public function testDatetimeTypeIsNullByDefault(): void
    {
        $this->assertNull($this->entity->getDatetimeType());
    }

    public function testSetAndGetNullableString(): void
    {
        $value = 'nullable value';
        $this->entity->setNullableString($value);
        $this->assertEquals($value, $this->entity->getNullableString());
        
        $this->entity->setNullableString(null);
        $this->assertNull($this->entity->getNullableString());
    }

    public function testNullableStringIsNullByDefault(): void
    {
        $this->assertNull($this->entity->getNullableString());
    }

    public function testToStringWithStringType(): void
    {
        $value = 'Test Entity';
        $this->entity->setStringType($value);
        $this->assertEquals($value, (string) $this->entity);
    }

    public function testToStringWithoutStringType(): void
    {
        $stringValue = (string) $this->entity;
        $this->assertStringStartsWith('TestEntityWithAllTypes#', $stringValue);
    }
}