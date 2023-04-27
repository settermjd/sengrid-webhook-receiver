<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use SendGrid\EventWebhook\EventWebhook;

use function sprintf;

readonly class SendGridWebhookHandler implements RequestHandlerInterface
{
    public const HEADER_WEBHOOK_SIGNATURE = 'X-Twilio-Email-Event-Webhook-Signature';
    public const HEADER_WEBHOOK_TIMESTAMP = 'X-Twilio-Email-Event-Webhook-Timestamp';

    public function __construct(private LoggerInterface $logger, private string $publicKey)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (! $request->hasHeader(self::HEADER_WEBHOOK_SIGNATURE)) {
            $message = sprintf(
                "Request did not include the %s header.",
                self::HEADER_WEBHOOK_SIGNATURE
            );
            $this->logger->debug($message);

            return new TextResponse($message);
        }

        if (! $request->hasHeader(self::HEADER_WEBHOOK_TIMESTAMP)) {
            $message = sprintf(
                "Request did not include the %s header.",
                self::HEADER_WEBHOOK_TIMESTAMP
            );
            $this->logger->debug($message);

            return new TextResponse($message);
        }

        $requestBody = $request->getBody()->getContents();
        $signature   = $request->getHeaderLine(self::HEADER_WEBHOOK_SIGNATURE);
        $timestamp   = $request->getHeaderLine(self::HEADER_WEBHOOK_TIMESTAMP);

        $eventWebHook      = new EventWebhook();
        $verifiedSignature = $eventWebHook->verifySignature(
            $eventWebHook->convertPublicKeyToECDSA($this->publicKey),
            $requestBody,
            $signature,
            $timestamp
        );

        $message = $verifiedSignature
            ? 'SendGrid webhook data successfully validated.'
            : 'SendGrid webhook data did not successfully validate.';
        $this->logger->debug($message);

        return new EmptyResponse();
    }
}
