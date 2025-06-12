<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Service;

use Doctrine\DBAL\Exception as DbalException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Tourze\DoctrineDirectInsertBundle\DoctrineDirectInsertBundle;
use Tourze\DoctrineDirectInsertBundle\Service\DirectInsertService;
use Tourze\DoctrineDirectInsertBundle\Tests\Entity\TestEntity;
use Tourze\DoctrineDirectInsertBundle\Tests\Entity\TestEntityWithAllTypes;
use Tourze\DoctrineDirectInsertBundle\Tests\Entity\TestEntityWithPresetId;
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

    public function test_directInsert_withPresetId_usesProvidedId(): void
    {
        // Arrange
        $entity = new TestEntityWithPresetId();
        $presetId = 12345;
        $entity->setId($presetId);
        $entity->setName('test with preset id');

        // Act
        $returnedId = $this->service->directInsert($entity);

        // Assert
        $this->assertEquals($presetId, $returnedId);

        $this->entityManager->clear();

        $persistedEntity = $this->entityManager->find(TestEntityWithPresetId::class, $presetId);

        $this->assertNotNull($persistedEntity);
        $this->assertEquals($presetId, $persistedEntity->getId());
        $this->assertEquals('test with preset id', $persistedEntity->getName());
    }

    public function test_directInsert_withVariousDataTypes_persistsCorrectly(): void
    {
        // Arrange
        $entity = new TestEntityWithAllTypes();
        $entity->setStringType('a string');
        $entity->setTextType('a long text');
        $entity->setIntegerType(999);
        $entity->setFloatType(123.45);
        $entity->setBooleanType(true);
        $entity->setNullableString(null);

        // Act
        $id = $this->service->directInsert($entity);

        // Assert
        $this->assertIsNumeric($id);

        $this->entityManager->clear();
        $persistedEntity = $this->entityManager->find(TestEntityWithAllTypes::class, $id);

        $this->assertNotNull($persistedEntity);
        $this->assertEquals('a string', $persistedEntity->getStringType());
        $this->assertEquals('a long text', $persistedEntity->getTextType());
        $this->assertEquals(999, $persistedEntity->getIntegerType());
        $this->assertEquals(123.45, $persistedEntity->getFloatType());
        $this->assertTrue($persistedEntity->getBooleanType());
        $this->assertNull($persistedEntity->getNullableString());
    }

    public function test_directInsert_withDuplicatePrimaryKey_throwsDbalException(): void
    {
        // Arrange
        $this->expectException(DbalException::class);

        $presetId = 54321;
        $entity1 = new TestEntityWithPresetId();
        $entity1->setId($presetId);
        $entity1->setName('first entity');

        $entity2 = new TestEntityWithPresetId();
        $entity2->setId($presetId);
        $entity2->setName('second entity with same id');

        // Act
        $this->service->directInsert($entity1);
        $this->service->directInsert($entity2); // This should fail
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
