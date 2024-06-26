<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Bus\Command;

use CompanyName\Shared\Domain\Bus\Command\Command;
use CompanyName\Shared\Domain\Bus\Command\CommandBus;
use CompanyName\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use React\EventLoop\Loop;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final readonly class InMemoryAsyncCommandBus implements CommandBus
{
	private MessageBus $bus;

	public function __construct(iterable $commandHandlers)
	{
		$this->bus = new MessageBus(
			[
				new HandleMessageMiddleware(
					new HandlersLocator(CallableFirstParameterExtractor::forCallables($commandHandlers))
				),
			]
		);
	}

	public function dispatch(Command $command): void
	{
		$loop = Loop::get();

		$loop->futureTick(function () use ($command): void {
			try {
				$this->bus->dispatch($command);
			} catch (NoHandlerForMessageException) {
				throw new CommandNotRegisteredError($command);
			} catch (HandlerFailedException $error) {
				throw $error->getPrevious() ?? $error;
			}
		});
	}
}
