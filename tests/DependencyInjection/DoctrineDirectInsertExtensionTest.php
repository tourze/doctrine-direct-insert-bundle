<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Tourze\DoctrineDirectInsertBundle\DependencyInjection\DoctrineDirectInsertExtension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(DoctrineDirectInsertExtension::class)]
final class DoctrineDirectInsertExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private DoctrineDirectInsertExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new DoctrineDirectInsertExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testExtensionInheritsFromCorrectBase(): void
    {
        // Assert
        $this->assertInstanceOf(Extension::class, $this->extension);
    }
}
