<?php

declare(strict_types=1);

namespace CompanyName\Shared\Domain\ValueObject;

interface ValueObject
{
	/**
	 * @return string|int|float
	 */
	public function value(): mixed;
}
