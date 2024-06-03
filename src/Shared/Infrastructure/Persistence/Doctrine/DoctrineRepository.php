<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;

abstract readonly class DoctrineRepository
{
	public function __construct(private EntityManager $entityManager) {}

	protected function entityManager(): EntityManager
	{
		return $this->entityManager;
	}

	protected function persist(object $entity): void
	{
		$this->entityManager()->persist($entity);
		$this->entityManager()->flush($entity);
	}

	protected function remove(object $entity): void
	{
		$this->entityManager()->remove($entity);
		$this->entityManager()->flush($entity);
	}

	/**
	 * @template T of object
	 *
	 * @psalm-param class-string<T> $entityClass
	 *
	 * @psalm-return EntityRepository<T>
	 *
	 * @throws NotSupported
	 */
	protected function repository(string $entityClass): EntityRepository
	{
		return $this->entityManager->getRepository($entityClass);
	}
}
