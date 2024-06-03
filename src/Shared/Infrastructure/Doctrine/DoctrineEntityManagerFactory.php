<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Doctrine;

use CompanyName\Shared\Infrastructure\Doctrine\Dbal\DbalCustomTypesRegistrar;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\ORMSetup;

final class DoctrineEntityManagerFactory
{
	public static function create(array $parameters, string $environment): EntityManager
	{
		$isDevMode = $environment !== 'prod';

		$prefixes = array_merge(DoctrinePrefixesSearcher::inPath(__DIR__ . '/../../../../src', 'CompanyName'),);

		$dbalCustomTypesClasses = DbalTypesSearcher::inPath(__DIR__ . '/../../../../src');

		DbalCustomTypesRegistrar::register($dbalCustomTypesClasses);

		$config = self::createConfiguration($prefixes, $isDevMode);

		$config->setSchemaAssetsFilter(static function (object|string $assetName): bool {
			if ($assetName instanceof AbstractAsset) {
				$assetName = $assetName->getName();
			}
			/** @var string $assetName */
			return (bool) preg_match('/^(?!tiger)(?!topology)/', $assetName);
		});

		$connection = DriverManager::getConnection($parameters, $config, new EventManager());

		return new EntityManager($connection, $config);
	}

	private static function createConfiguration(array $prefixes, bool $isDevMode): Configuration
	{
		$config = ORMSetup::createConfiguration($isDevMode);

		$config->setMetadataDriverImpl(new SimplifiedXmlDriver($prefixes));

		return $config;
	}
}
