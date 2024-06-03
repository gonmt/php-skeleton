<?php

declare(strict_types=1);

namespace CompanyName\Shared\Domain\ValueObject;

abstract readonly class FloatValueObject implements ValueObject
{
	final public function __construct(protected float $value)
	{
		if (method_exists($this, 'validateValue')) {
			$this->validateValue($value);
		}
	}

	public function value(): float
	{
		return $this->value;
	}

	public static function create(float $value): static
	{
		return new static($value);
	}

	public function equals(self $other): bool
	{
		return $other === $this;
	}
}
