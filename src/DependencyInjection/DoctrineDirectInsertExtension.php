<?php

namespace Tourze\DoctrineDirectInsertBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AppendDoctrineConnectionExtension;

class DoctrineDirectInsertExtension extends AppendDoctrineConnectionExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }

    protected function getDoctrineConnectionName(): string
    {
        return 'direct_insert';
    }
}
