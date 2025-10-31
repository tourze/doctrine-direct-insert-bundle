# Doctrine Direct Insert Bundle

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](#)

[English](README.md) | [中文](README.zh-CN.md)

一个提供 Doctrine 实体直接数据库插入功能的 Symfony 包，无需使用 ORM 的 persist/flush 机制。此包专为需要直接向数据库插入数据的高性能场景设计。

## 特性

- 直接数据库插入，无 ORM 开销
- 支持自动生成和预设 ID
- 专用数据库连接支持
- 兼容各种数据类型（字符串、文本、整数、浮点数、布尔值、可空字段）
- 基于实体元数据自动生成 SQL

## 安装

```bash
composer require tourze/doctrine-direct-insert-bundle
```

## 配置

将包添加到您的 `config/bundles.php`：

```php
<?php

return [
    // ... 其他包
    Tourze\DoctrineDirectInsertBundle\DoctrineDirectInsertBundle::class => ['all' => true],
];
```

## 快速开始

### 基本用法

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
        $entity->setName('示例名称');
        $entity->setEmail('example@example.com');
        
        // 直接插入数据库并获取 ID
        $id = $this->directInsertService->directInsert($entity);
        
        return new JsonResponse(['id' => $id]);
    }
}
```

### 使用预设 ID

```php
<?php

$entity = new MyEntity();
$entity->setId(12345); // 设置特定的 ID
$entity->setName('带预设 ID 的示例');

$returnedId = $this->directInsertService->directInsert($entity);
// $returnedId 将是 12345
```

### 实体示例

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

    // ... getters 和 setters
}
```

## 高级用法

### 专用连接

此包支持使用 `@WithDedicatedConnection` 属性的专用数据库连接。该服务使用名为 `direct_insert` 的专用连接通道，以提高性能和隔离性。

### 数据类型支持

该包支持所有标准的 Doctrine 数据类型：
- 字符串和文本类型
- 整数和浮点数类型
- 布尔类型
- 可空字段
- 自动生成的 ID
- 预设 ID

## 性能优势

- **无 ORM 开销**：绕过 Doctrine 的工作单元以实现更快的插入
- **专用连接**：使用单独的数据库连接以避免冲突
- **直接 SQL**：直接生成优化的 INSERT 语句
- **批量操作**：适合大量数据插入场景

## 要求

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+
- Doctrine DBAL 4.0+

## 依赖

此包依赖于：

- `tourze/doctrine-entity-checker-bundle` - 用于 SQL 格式化工具

## 许可证

此包在 MIT 许可证下授权。详情请参阅 [LICENSE](LICENSE) 文件。