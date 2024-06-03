<?php

declare(strict_types=1);

namespace CompanyName\Apps\FirstApp\Backend\Controller\HealthCheck;

use Symfony\Component\HttpFoundation\JsonResponse;

final class HealthCheckGetController
{
	public function __invoke(): JsonResponse
	{
		return new JsonResponse(
			[
				'firstapp-backend-status' => 'ok',
			]
		);
	}
}
