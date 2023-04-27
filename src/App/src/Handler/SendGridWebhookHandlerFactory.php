<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function assert;

class SendGridWebhookHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $logger = $container->get(LoggerInterface::class);
        assert($logger instanceof LoggerInterface);

        $config = $container->get('config');
        $publicKey = $config['sendgrid']['webhook']['public_key'] ?? null;
        assert($publicKey !== null);

        return new SendGridWebhookHandler($logger, $publicKey);
    }
}
