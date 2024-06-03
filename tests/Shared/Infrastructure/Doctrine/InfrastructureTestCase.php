<?php


declare(strict_types=1);

namespace CompanyName\Tests\Shared\Infrastructure\Doctrine;

use CompanyName\Apps\FirstApp\Backend\FirstAppBackendKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class InfrastructureTestCase extends KernelTestCase
{
	protected function service(string $id): mixed
	{
		return $this->getContainer()->get($id);
	}

	public static function getKernelClass(): string
	{
		return FirstAppBackendKernel::class;
	}
}
