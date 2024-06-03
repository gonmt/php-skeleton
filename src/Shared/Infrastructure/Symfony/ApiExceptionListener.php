<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Symfony;

use CompanyName\Shared\Domain\DomainError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final readonly class ApiExceptionListener
{
	public function __construct(private ApiExceptionsHttpStatusCodeMapping $exceptionHandler) {}

	public function onException(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();
		$response = $exception instanceof DomainError ? $this->domainErrorResponse(
			$exception
		) : $this->unexpectedErrorResponse();

		$event->setResponse($response);
	}

	private function domainErrorResponse(DomainError $error): JsonResponse
	{
		$statusCode = $this->exceptionHandler->statusCodeFor($error::class);

		return new JsonResponse(
			[
				'success' => false,
				'status' => $statusCode,
				'code' => $error->errorCode(),
				'title' => $error->errorMessage(),
				'detail' => $error->errorDescription(),
			],
			$statusCode,
		);
	}

	private function unexpectedErrorResponse(): JsonResponse
	{
		$statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

		return new JsonResponse(
			[
				'success' => false,
				'status' => $statusCode,
				'code' => 'unexpected_error',
				'title' => 'Unexpected error',
				'detail' => 'Unexpected error',
			],
			$statusCode,
		);
	}
}
