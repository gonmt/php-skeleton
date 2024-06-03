<?php

declare(strict_types=1);

namespace CompanyName\Tests;

use DateTimeImmutable;
use DateTimeInterface;
use DomainException;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Stringable;

final class ArchitectureTest
{
	public function test_shared_domain_should_only_import_itself_and_language_classes(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('CompanyName\Shared\Domain'))
			->canOnlyDependOn()
			->classes(
				Selector::inNamespace('CompanyName\Shared\Domain'),
				Selector::classname(DomainException::class),
				Selector::classname(Stringable::class),
				Selector::classname(Uuid::class),
				Selector::classname(DateTimeImmutable::class),
				Selector::classname(DateTimeInterface::class),
				Selector::classname(RuntimeException::class),
			)
			->because('domain can only import itself and shared domain');
	}
}
