<?php

declare(strict_types=1);

namespace CompanyName\Shared\Domain;

use DomainException;

abstract class DomainError extends DomainException
{
	public function __construct()
	{
		parent::__construct($this->errorMessage());
	}

	abstract public function errorCode(): string;
	abstract public function errorMessage(): string;
	abstract public function errorDescription(): string;
}
