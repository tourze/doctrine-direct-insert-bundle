<?php

namespace Tourze\DoctrineDirectInsertBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Tourze\DoctrineEntityCheckerBundle\Service\SqlFormatter;

#[Autoconfigure(public: true)]
readonly class DirectInsertService
{
    public function __construct(
        private SqlFormatter $sqlFormatter,
        private EntityManagerInterface $entityManager,
        #[Autowire(service: 'doctrine.dbal.direct_insert_connection')] private Connection $connection,
    ) {
    }

    /**
     * 直接插入数据库
     */
    public function directInsert(object $object): int|string
    {
        [$tableName, $params] = $this->sqlFormatter->getObjectInsertSql($this->entityManager, $object);

        $this->connection->insert($tableName, $params);
        if (isset($params['id']) && '' !== $params['id']) {
            $id = $params['id'];
        } else {
            $id = $this->connection->lastInsertId();
        }

        return $id;
    }
}
