<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Logger;

use CompanyName\Shared\Domain\Logger;
use Psr\Log\LoggerInterface;

final readonly class MonologLogger implements Logger
{
	public function __construct(private LoggerInterface $logger) {}

	public function info(string $message, array $context = []): void
	{
		$this->logger->info($message, $context);
	}

	public function warning(string $message, array $context = []): void
	{
		$this->logger->warning($message, $context);
	}

	public function critical(string $message, array $context = []): void
	{
		$this->logger->critical($message, $context);
	}
}
