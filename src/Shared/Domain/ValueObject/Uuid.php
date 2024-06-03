<?php

declare(strict_types=1);

namespace CompanyName\Shared\Domain\ValueObject;

use CompanyName\Shared\Domain\ValueObject\Exception\InvalidUuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

abstract readonly class Uuid extends StringValueObject
{
	protected function validateValue(string $value): void
	{
		if (!RamseyUuid::isValid($value)) {
			throw new InvalidUuid();
		}
	}

	final public static function random(): self
	{
		return new static(RamseyUuid::uuid4()->toString());
	}
}
