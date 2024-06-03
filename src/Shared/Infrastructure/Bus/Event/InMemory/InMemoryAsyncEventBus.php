<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Bus\Event\InMemory;

use CompanyName\Shared\Domain\Bus\Event\DomainEvent;
use CompanyName\Shared\Domain\Bus\Event\EventBus;
use CompanyName\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use React\EventLoop\Loop;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final readonly class InMemoryAsyncEventBus implements EventBus
{
	private MessageBus $bus;

	public function __construct(iterable $subscribers)
	{
		$this->bus = new MessageBus(
			[new HandleMessageMiddleware(new HandlersLocator(CallableFirstParameterExtractor::forCallables($subscribers))), ]
		);
	}

	public function publish(DomainEvent ...$events): void
	{
		$loop = Loop::get();

		$loop->futureTick(function () use ($events): void {
			foreach ($events as $event) {
				try {
					$this->bus->dispatch($event);
				} catch (NoHandlerForMessageException) {
				}
			}
		});
	}
}
