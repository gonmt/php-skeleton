<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Doctrine;

use CompanyName\Shared\Domain\Utils;
use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;

final class DbalTypesSearcher
{
	private const string MAPPINGS_PATH = 'Infrastructure/Persistence/Doctrine';

	public static function inPath(string $path): array
	{
		$possibleDbalDirectories = self::possibleDbalPaths($path);
		$dbalDirectories = filter(self::isExistingDbalPath(), $possibleDbalDirectories);

		return reduce(self::dbalClassesSearcher(), $dbalDirectories, []);
	}

	private static function modulesInPath(string $path): array
	{
		return filter(
			static fn (string $possibleModule): bool => !in_array($possibleModule, ['.', '..'], true),
			scandir($path)
		);
	}

	private static function possibleDbalPaths(string $path): array
	{
		return map(
			static function (mixed $_unused, string $module) use ($path) {
				$mappingsPath = self::MAPPINGS_PATH;

				return realpath("$path/$module/$mappingsPath");
			},
			array_flip(self::modulesInPath($path))
		);
	}

	private static function isExistingDbalPath(): callable
	{
		return static fn (string $path): bool => !empty($path);
	}

	private static function dbalClassesSearcher(): callable
	{
		return static function (array $totalNamespaces, string $path): array {
			$possibleFiles = scandir($path);
			$files = filter(static fn (string $file): bool => Utils::endsWith('Type.php', $file), $possibleFiles);

			$namespaces = map(
				static function (string $file) use ($path): string {
					$fullPath = "$path/$file";
					$splittedPath = explode('/src/', $fullPath);

					$classWithoutPrefix = str_replace(['.php', '/'], ['', '\\'], $splittedPath[1]);

					return "CompanyName\\$classWithoutPrefix";
				},
				$files
			);

			return array_merge($totalNamespaces, $namespaces);
		};
	}
}
