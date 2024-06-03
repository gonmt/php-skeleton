<?php

declare(strict_types=1);

namespace CompanyName\Shared\Domain\Bus\Event;

interface DomainEventSubscriber
{
	public static function subscribedTo(): array;
}
