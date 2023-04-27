<?php

declare(strict_types=1);

namespace App\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $log = new Logger('webhook-logger');
        $log->pushHandler(new StreamHandler(__DIR__ . '/../../../../data/log/webhook.log' ));

        return $log;
    }
}