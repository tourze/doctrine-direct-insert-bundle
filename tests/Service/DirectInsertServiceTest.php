<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Tourze\DoctrineDirectInsertBundle\DoctrineDirectInsertBundle;
use Tourze\DoctrineDirectInsertBundle\Service\DirectInsertService;
use Tourze\DoctrineDirectInsertBundle\Tests\Entity\TestEntity;
use Tourze\IntegrationTestKernel\IntegrationTestKernel;

class DirectInsertServiceTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private DirectInsertService $service;

    protected static function createKernel(array $options = []): KernelInterface
    {
        $env = $options['environment'] ?? $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'test';
        $debug = $options['debug'] ?? $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? true;

        return new IntegrationTestKernel(
            $env,
            $debug,
            [
                DoctrineDirectInsertBundle::class => ['all' => true],
            ],
            [
                'Tourze\DoctrineDirectInsertBundle\Tests\Entity' => __DIR__ . '/../Entity',
            ]
        );
    }

    public function test_directInsert_withNewEntity_persistsToDatabase(): void
    {
        // Arrange
        $entity = new TestEntity();
        $entity->setName('test name');

        // Act
        $id = $this->service->directInsert($entity);

        // Assert
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);

        $this->entityManager->clear();

        $persistedEntity = $this->entityManager->find(TestEntity::class, $id);

        $this->assertNotNull($persistedEntity);
        $this->assertInstanceOf(TestEntity::class, $persistedEntity);
        $this->assertEquals('test name', $persistedEntity->getName());
    }

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->service = $container->get(DirectInsertService::class);

        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    protected function tearDown(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($metadata);

        self::ensureKernelShutdown();
        parent::tearDown();
    }
}
