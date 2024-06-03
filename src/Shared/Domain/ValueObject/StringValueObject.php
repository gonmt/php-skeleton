<?php

declare(strict_types=1);

namespace CompanyName\Shared\Domain\ValueObject;

use Stringable;

abstract readonly class StringValueObject implements Stringable, ValueObject
{
	final public function __construct(protected string $value)
	{
		if (method_exists($this, 'validateValue')) {
			$this->validateValue($value);
		}
	}

	public function value(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}

	public static function create(string $value): static
	{
		return new static($value);
	}

	public function equals(self $other): bool
	{
		return $other === $this;
	}
}
