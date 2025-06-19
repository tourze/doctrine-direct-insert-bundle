<?php

namespace Tourze\DoctrineDirectInsertBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineDedicatedConnectionBundle\DoctrineDedicatedConnectionBundle;
use Tourze\DoctrineEntityCheckerBundle\DoctrineEntityCheckerBundle;

class DoctrineDirectInsertBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            DoctrineEntityCheckerBundle::class => ['all' => true],
            DoctrineDedicatedConnectionBundle::class => ['all' => true],
        ];
    }
}
