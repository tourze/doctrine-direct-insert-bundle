<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\DoctrineDirectInsertBundle\DependencyInjection\DoctrineDirectInsertExtension;

class DoctrineDirectInsertExtensionTest extends TestCase
{
    private DoctrineDirectInsertExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new DoctrineDirectInsertExtension();
        $this->container = new ContainerBuilder();
    }

    public function testLoad(): void
    {
        // Act
        $this->extension->load([], $this->container);

        // Assert - 验证服务定义已加载
        $this->assertTrue($this->container->hasDefinition('Tourze\DoctrineDirectInsertBundle\Service\DirectInsertService'));
    }

    public function testLoadWithConfigs(): void
    {
        // Arrange
        $configs = [
            ['some_config' => 'value']
        ];

        // Act & Assert - 确保不会抛出异常
        $this->expectNotToPerformAssertions();
        $this->extension->load($configs, $this->container);
    }

    public function testExtensionInheritsFromCorrectBase(): void
    {
        // Assert
        $this->assertInstanceOf(\Symfony\Component\DependencyInjection\Extension\Extension::class, $this->extension);
    }
} 