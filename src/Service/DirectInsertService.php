<?php

namespace Tourze\DoctrineDirectInsertBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\DoctrineDedicatedConnectionBundle\Attribute\WithDedicatedConnection;
use Tourze\DoctrineEntityCheckerBundle\Service\SqlFormatter;

#[Autoconfigure(public: true)]
#[WithDedicatedConnection('direct_insert')]
class DirectInsertService
{
    public function __construct(
        private readonly SqlFormatter $sqlFormatter,
        private readonly EntityManagerInterface $entityManager,
        private readonly Connection $connection,
    )
    {
    }

    private function getConnection(): Connection
    {
        return $this->entityManager->getConnection();
    }

    /**
     * 直接插入数据库
     */
    public function directInsert(object $object): int|string
    {
        [$tableName, $params] = $this->sqlFormatter->getObjectInsertSql($this->entityManager, $object);

        $this->connection->insert($tableName, $params);
        if (!empty($params['id'])) {
            $id = $params['id'];
        } else {
            $id =  $this->connection->lastInsertId();
        }
        return $id;
    }
}
