<?php

namespace Tourze\DoctrineDirectInsertBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Tourze\DoctrineEntityCheckerBundle\Service\SqlFormatter;

class DirectInsertService
{
    public function __construct(
        private readonly SqlFormatter $sqlFormatter,
        private readonly EntityManagerInterface $entityManager,
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

        $this->getConnection()->insert($tableName, $params);
        if (!empty($params['id'])) {
            $id = $params['id'];
        } else {
            $id =  $this->getConnection()->lastInsertId();
        }
        return $id;
    }
}
