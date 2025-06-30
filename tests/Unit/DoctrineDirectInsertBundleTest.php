<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineDirectInsertBundle\DoctrineDirectInsertBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Tourze\DoctrineDedicatedConnectionBundle\DoctrineDedicatedConnectionBundle;
use Tourze\DoctrineEntityCheckerBundle\DoctrineEntityCheckerBundle;

class DoctrineDirectInsertBundleTest extends TestCase
{
    private DoctrineDirectInsertBundle $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bundle = new DoctrineDirectInsertBundle();
    }

    public function testGetBundleDependencies(): void
    {
        $dependencies = DoctrineDirectInsertBundle::getBundleDependencies();
        
        $this->assertArrayHasKey(DoctrineBundle::class, $dependencies);
        $this->assertArrayHasKey(DoctrineEntityCheckerBundle::class, $dependencies);
        $this->assertArrayHasKey(DoctrineDedicatedConnectionBundle::class, $dependencies);
        
        $this->assertEquals(['all' => true], $dependencies[DoctrineBundle::class]);
        $this->assertEquals(['all' => true], $dependencies[DoctrineEntityCheckerBundle::class]);
        $this->assertEquals(['all' => true], $dependencies[DoctrineDedicatedConnectionBundle::class]);
    }

    public function testBundleInstantiation(): void
    {
        $this->assertInstanceOf(DoctrineDirectInsertBundle::class, $this->bundle);
    }
}