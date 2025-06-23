<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'test_entity_all_types', options: ['comment' => '所有类型测试实体'])]
class TestEntityWithAllTypes implements \Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '主键ID'])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, options: ['comment' => '字符串类型'])]
    private string $stringType;

    #[ORM\Column(type: Types::TEXT, options: ['comment' => '文本类型'])]
    private string $textType;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '整数类型'])]
    private int $integerType;

    #[ORM\Column(type: Types::FLOAT, options: ['comment' => '浮点类型'])]
    private float $floatType;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '布尔类型'])]
    private bool $booleanType;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '日期时间类型'])]
    private ?DateTimeImmutable $datetimeType = null;

    #[ORM\Column(type: Types::STRING, nullable: true, options: ['comment' => '可空字符串'])]
    private ?string $nullableString = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStringType(): string
    {
        return $this->stringType;
    }

    public function setStringType(string $stringType): void
    {
        $this->stringType = $stringType;
    }

    public function getTextType(): string
    {
        return $this->textType;
    }

    public function setTextType(string $textType): void
    {
        $this->textType = $textType;
    }

    public function getIntegerType(): int
    {
        return $this->integerType;
    }

    public function setIntegerType(int $integerType): void
    {
        $this->integerType = $integerType;
    }

    public function getFloatType(): float
    {
        return $this->floatType;
    }

    public function setFloatType(float $floatType): void
    {
        $this->floatType = $floatType;
    }

    public function getBooleanType(): bool
    {
        return $this->booleanType;
    }

    public function setBooleanType(bool $booleanType): void
    {
        $this->booleanType = $booleanType;
    }

    public function getDatetimeType(): ?DateTimeImmutable
    {
        return $this->datetimeType;
    }

    public function setDatetimeType(?DateTimeImmutable $datetimeType): void
    {
        $this->datetimeType = $datetimeType;
    }

    public function getNullableString(): ?string
    {
        return $this->nullableString;
    }

    public function setNullableString(?string $nullableString): void
    {
        $this->nullableString = $nullableString;
    }

    public function __toString(): string
    {
        return $this->stringType ?? 'TestEntityWithAllTypes#' . $this->id;
    }
} 