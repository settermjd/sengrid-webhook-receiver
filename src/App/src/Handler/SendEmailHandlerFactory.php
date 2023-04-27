<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;

class SendEmailHandlerFactory
{
    public function __invoke(ContainerInterface $container) : SendEmailHandler
    {
        $sendGrid = $container->get(\SendGrid::class);
        assert($sendGrid instanceof \SendGrid);

        return new SendEmailHandler($sendGrid);
    }
}
