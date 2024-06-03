<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Symfony;

use CompanyName\Shared\Domain\Bus\Command\Command;
use CompanyName\Shared\Domain\Bus\Command\CommandBus;
use CompanyName\Shared\Domain\Bus\Query\Query;
use CompanyName\Shared\Domain\Bus\Query\QueryBus;
use CompanyName\Shared\Domain\Bus\Query\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use function Lambdish\Phunctional\each;

abstract readonly class ApiController
{
	public function __construct(
		private QueryBus $queryBus,
		private CommandBus $commandBus,
		ApiExceptionsHttpStatusCodeMapping $exceptionHandler
	) {
		each(
			fn (int $httpCode, string $exceptionClass) => $exceptionHandler->register($exceptionClass, $httpCode),
			$this->exceptions()
		);
	}

	abstract protected function exceptions(): array;

	protected function ask(Query $query): ?Response
	{
		return $this->queryBus->ask($query);
	}

	protected function dispatch(Command $command): void
	{
		$this->commandBus->dispatch($command);
	}

	protected function successResponse(array $data, int $httpStatusCode = 200): JsonResponse
	{
		return new JsonResponse([
			'success' => true,
			'data' => $data,
		], $httpStatusCode);
	}
}
