<?php

declare(strict_types=1);

namespace App\Handler;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SendGrid;
use SendGrid\Mail\Mail;

readonly class SendEmailHandler implements RequestHandlerInterface
{
    public function __construct(private SendGrid $sendGrid)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = new Mail();
        $email->setFrom($_SERVER['SENDGRID_SENDER_ADDRESS'], $_SERVER['SENDGRID_SENDER_NAME']);
        $email->setSubject("Sending with Twilio SendGrid is Fun");
        $email->addTo($_SERVER['SENDGRID_RECIPIENT_ADDRESS'], $_SERVER['SENDGRID_RECIPIENT_NAME']);
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
            "text/html",
            "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        try {
            $response = $this->sendGrid->send($email);
            return new JsonResponse(
                [
                    'status'  => $response->statusCode(),
                    'headers' => $response->headers(),
                    'body'    => $response->body(),
                ]
            );
        } catch (Exception $e) {
            return new TextResponse('Caught exception: ' . $e->getMessage() . "\n");
        }
    }
}
