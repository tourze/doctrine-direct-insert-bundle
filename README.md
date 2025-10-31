# Doctrine Direct Insert Bundle

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](#)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle that provides direct database insertion capabilities for Doctrine entities without using the ORM's persist/flush mechanism. 

This bundle is designed for high-performance scenarios where you need to insert data directly into the database.

## Features

- Direct database insertion without ORM overhead
- Support for auto-generated and preset IDs
- Dedicated database connection support
- Compatible with various data types (string, text, integer, float, boolean, nullable fields)
- Automatic SQL generation based on entity metadata

## Installation

```bash
composer require tourze/doctrine-direct-insert-bundle
```

## Configuration

Add the bundle to your `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Tourze\DoctrineDirectInsertBundle\DoctrineDirectInsertBundle::class => ['all' => true],
];
```

## Quick Start

### Basic Usage

```php
<?php

use Tourze\DoctrineDirectInsertBundle\Service\DirectInsertService;

class MyController
{
    public function __construct(
        private readonly DirectInsertService $directInsertService
    ) {
    }

    public function createEntity(): Response
    {
        $entity = new MyEntity();
        $entity->setName('Example Name');
        $entity->setEmail('example@example.com');
        
        // Insert directly into database and get the ID
        $id = $this->directInsertService->directInsert($entity);
        
        return new JsonResponse(['id' => $id]);
    }
}
```

### With Preset ID

```php
<?php

$entity = new MyEntity();
$entity->setId(12345); // Set a specific ID
$entity->setName('Example with preset ID');

$returnedId = $this->directInsertService->directInsert($entity);
// $returnedId will be 12345
```

### Entity Example

```php
<?php

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'my_entity')]
class MyEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $email;

    // ... getters and setters
}
```

## Advanced Usage

### Dedicated Connection

This bundle supports dedicated database connections using the `@WithDedicatedConnection` attribute. The service uses a dedicated connection channel called `direct_insert` for improved performance and isolation.

### Data Type Support

The bundle supports all standard Doctrine data types:
- String and Text types
- Integer and Float types  
- Boolean types
- Nullable fields
- Auto-generated IDs
- Preset IDs

## Performance Benefits

- **No ORM Overhead**: Bypasses Doctrine's Unit of Work for faster insertions
- **Dedicated Connection**: Uses a separate database connection to avoid conflicts
- **Direct SQL**: Generates optimized INSERT statements directly
- **Batch Operations**: Suitable for high-volume data insertion scenarios

## Requirements

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+
- Doctrine DBAL 4.0+

## Dependencies

This bundle depends on:

- `tourze/doctrine-entity-checker-bundle` - For SQL formatting utilities

## License

This bundle is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.