<?php

declare(strict_types=1);

namespace CompanyName\Tests\Shared\Domain\ValueObject;

use CompanyName\Shared\Domain\ValueObject\Exception\InvalidUuid;
use CompanyName\Shared\Domain\ValueObject\Uuid;
use Mockery\Adapter\Phpunit\MockeryTestCase;

final readonly class UuidSpy extends Uuid {}

final class UuidTest extends MockeryTestCase
{
	/**
	 * @test
	 */
	public function it_should_throw_invalid_uuid_exception(): void
	{
		$this->expectException(InvalidUuid::class);

		UuidSpy::create('1234567890');
	}
}
