<?php

declare(strict_types=1);

namespace Tourze\DoctrineDirectInsertBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineDirectInsertBundle\DoctrineDirectInsertBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(DoctrineDirectInsertBundle::class)]
#[RunTestsInSeparateProcesses]
final class DoctrineDirectInsertBundleTest extends AbstractBundleTestCase
{
    public function testBundleImplementsBundleDependencyInterface(): void
    {
        // @phpstan-ignore-next-line integrationTest.noDirectInstantiationOfCoveredClass
        $bundle = new DoctrineDirectInsertBundle();
        $this->assertInstanceOf(BundleDependencyInterface::class, $bundle);
    }
}
