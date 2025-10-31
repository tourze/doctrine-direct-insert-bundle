<?php

declare(strict_types=1);

namespace Tourze\DoctrineDirectInsertBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineDirectInsertBundle\Service\DirectInsertService;
use Tourze\DoctrineTrackBundle\Entity\EntityTrackLog;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(DirectInsertService::class)]
#[RunTestsInSeparateProcesses]
final class DirectInsertServiceTest extends AbstractIntegrationTestCase
{
    private DirectInsertService $service;

    protected function onSetUp(): void
    {
        $this->service = self::getService(DirectInsertService::class);
    }

    public function testDirectInsertWithNewEntityPersistsToDatabase(): void
    {
        $entity = $this->createEntityTrackLog('direct_insert_new_' . uniqid('', true));

        $id = $this->service->directInsert($entity);

        self::assertNotSame('', $id);
        self::assertGreaterThan(0, (int) $id);

        $entityManager = self::getEntityManager();
        $entityManager->clear();
        $persisted = $entityManager->find(EntityTrackLog::class, (int) $id);

        self::assertInstanceOf(EntityTrackLog::class, $persisted);
        self::assertSame($entity->getObjectClass(), $persisted->getObjectClass());
        self::assertSame($entity->getObjectId(), $persisted->getObjectId());
    }

    public function testDirectInsertWithPresetIdUsesProvidedId(): void
    {
        $presetId = random_int(1000, 9999);
        $entity = $this->createEntityTrackLog('direct_insert_preset_' . $presetId);
        $entity->setId($presetId);

        $returnedId = $this->service->directInsert($entity);

        self::assertSame($presetId, (int) $returnedId);

        $entityManager = self::getEntityManager();
        $entityManager->clear();
        $persisted = $entityManager->find(EntityTrackLog::class, $presetId);

        self::assertInstanceOf(EntityTrackLog::class, $persisted);
        self::assertSame($entity->getObjectClass(), $persisted->getObjectClass());
        self::assertSame($entity->getObjectId(), $persisted->getObjectId());
    }

    private function createEntityTrackLog(string $objectId): EntityTrackLog
    {
        $log = new EntityTrackLog();
        $log->setObjectClass(self::class);
        $log->setObjectId($objectId);
        $log->setAction('create');
        $log->setData(['field' => 'value']);
        $log->setRequestId('request_' . uniqid('', true));
        $log->setCreateTime(new \DateTimeImmutable());
        $log->setCreatedBy('tester');
        $log->setCreatedFromIp('127.0.0.1');

        return $log;
    }
}
