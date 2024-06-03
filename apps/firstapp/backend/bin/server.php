<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;
use CompanyName\Apps\FirstApp\Backend\FirstAppBackendKernel;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

require dirname(__DIR__) . '/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
	umask(0000);

	Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
	Request::setTrustedProxies(
		explode(',', (string) $trustedProxies),
		Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO
	);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
	Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new FirstAppBackendKernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);

$http = new React\Http\HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) use ($kernel): ResponseInterface {
    $httpFoundationFactory = new HttpFoundationFactory();
    $psr7Factory = new PsrHttpFactory();

    $request = $httpFoundationFactory->createRequest($request);
    $response = $kernel->handle($request);

    return $psr7Factory->createResponse($response);
});

$socket = new React\Socket\SocketServer('0.0.0.0:8080');

$http->listen($socket);
