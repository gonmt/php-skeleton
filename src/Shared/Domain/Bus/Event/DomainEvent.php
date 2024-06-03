<?php

declare(strict_types=1);

namespace CompanyName\Shared\Domain\Bus\Event;

use CompanyName\Shared\Domain\Utils;
use CompanyName\Shared\Domain\ValueObject\SimpleUuid;
use DateTimeImmutable;

abstract readonly class DomainEvent
{
	private string $eventId;
	private string $occurredOn;

	public function __construct(private string $aggregateId, ?string $eventId = null, ?string $occurredOn = null)
	{
		$this->eventId = is_null($eventId) ? SimpleUuid::random()->value() : $eventId;
		$this->occurredOn = is_null($occurredOn) ? Utils::dateToString(new DateTimeImmutable()) : $occurredOn;
	}

	abstract public static function fromPrimitives(
		string $aggregateId,
		array $body,
		string $eventId,
		string $occurredOn
	): self;

	abstract public static function eventName(): string;

	abstract public function toPrimitives(): array;

	final public function aggregateId(): string
	{
		return $this->aggregateId;
	}

	final public function eventId(): string
	{
		return $this->eventId;
	}

	final public function occurredOn(): string
	{
		return $this->occurredOn;
	}
}
