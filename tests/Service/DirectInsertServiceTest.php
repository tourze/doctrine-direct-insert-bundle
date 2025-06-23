<?php

namespace Tourze\DoctrineDirectInsertBundle\Tests\Service;

use Doctrine\DBAL\Connection;
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
    private Connection $connection;

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

        // 使用相同的连接直接查询
        $result = $this->connection->fetchAssociative(
            'SELECT * FROM test_entity WHERE id = ?',
            [$id]
        );

        $this->assertNotFalse($result);
        $this->assertEquals($id, $result['id']);
        $this->assertEquals('test name', $result['name']);
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

        // 使用相同的连接直接查询
        $result = $this->connection->fetchAssociative(
            'SELECT * FROM test_entity_preset_id WHERE id = ?',
            [$presetId]
        );

        $this->assertNotFalse($result);
        $this->assertEquals($presetId, $result['id']);
        $this->assertEquals('test with preset id', $result['name']);
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

        // 使用相同的连接直接查询
        $result = $this->connection->fetchAssociative(
            'SELECT * FROM test_entity_all_types WHERE id = ?',
            [$id]
        );

        $this->assertNotFalse($result);
        $this->assertEquals('a string', $result['string_type']);
        $this->assertEquals('a long text', $result['text_type']);
        $this->assertEquals(999, $result['integer_type']);
        $this->assertEquals(123.45, (float)$result['float_type']);
        $this->assertEquals(1, $result['boolean_type']); // SQLite 中布尔值存储为 0/1
        $this->assertNull($result['nullable_string']);
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
        
        // 获取 DirectInsertService 使用的连接
        $reflectionClass = new \ReflectionClass($this->service);
        $connectionProperty = $reflectionClass->getProperty('connection');
        $connectionProperty->setAccessible(true);
        $this->connection = $connectionProperty->getValue($this->service);

        // 确保测试实体被识别
        $testEntities = [
            TestEntity::class,
            TestEntityWithAllTypes::class,
            TestEntityWithPresetId::class,
        ];
        
        $metadata = [];
        foreach ($testEntities as $entityClass) {
            $metadata[] = $this->entityManager->getClassMetadata($entityClass);
        }
        
        // 在正确的连接上创建表
        $platform = $this->connection->getDatabasePlatform();
        $schemaTool = new SchemaTool($this->entityManager);
        
        // 获取 SQL 语句
        $dropSqls = $schemaTool->getDropSchemaSQL($metadata);
        $createSqls = $schemaTool->getCreateSchemaSql($metadata);
        
        // 在专用连接上执行 SQL
        foreach ($dropSqls as $sql) {
            try {
                $this->connection->executeStatement($sql);
            } catch (\Exception $e) {
                // 忽略删除表时的错误
            }
        }
        
        foreach ($createSqls as $sql) {
            $this->connection->executeStatement($sql);
        }
    }

    protected function tearDown(): void
    {
        // 确保测试实体被识别
        $testEntities = [
            TestEntity::class,
            TestEntityWithAllTypes::class,
            TestEntityWithPresetId::class,
        ];
        
        $metadata = [];
        foreach ($testEntities as $entityClass) {
            $metadata[] = $this->entityManager->getClassMetadata($entityClass);
        }
        
        $schemaTool = new SchemaTool($this->entityManager);
        $dropSqls = $schemaTool->getDropSchemaSQL($metadata);
        
        // 在专用连接上执行 SQL
        foreach ($dropSqls as $sql) {
            try {
                $this->connection->executeStatement($sql);
            } catch (\Exception $e) {
                // 忽略删除表时的错误
            }
        }

        self::ensureKernelShutdown();
        parent::tearDown();
    }
}
