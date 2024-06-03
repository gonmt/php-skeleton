<?php

declare(strict_types=1);

namespace CompanyName\Shared\Domain\ValueObject\Exception;

use CompanyName\Shared\Domain\DomainError;

final class InvalidUuid extends DomainError
{
	public function errorCode(): string
	{
		return 'invalid_uuid';
	}

	public function errorMessage(): string
	{
		return 'Only UUID values are allowed';
	}

	public function errorDescription(): string
	{
		return $this->errorMessage();
	}
}
