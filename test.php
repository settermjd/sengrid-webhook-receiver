<?php

require_once('vendor/autoload.php');

use SendGrid\EventWebhook\EventWebhook;

$payload = '{"email":"msetter@twilio.com","event":"delivered","ip":"149.72.123.24","response":"250 2.0.0 3q4dvt3m1d-1 Message accepted for delivery","sg_event_id":"ZGVsaXZlcmVkLTAtMjA0NTAxNTYtYVBlTHhpOEVSQk9qN1doVjJudEJSdy0w","sg_message_id":"aPeLxi8ERBOj7WhV2ntBRw.filterdrecv-7946957d94-bqdbw-1-644A5C0F-18.0","smtp-id":"<aPeLxi8ERBOj7WhV2ntBRw@geopod-ismtpd-1>","timestamp":1682594834,"tls":1}';
$publicKey = "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAE1LwoIxXtGRsqR9z0t9m4FIjZToJ+5YNTON3PA9JdBavN8sMqzM+p5Dcy9OgEaSxZnXKpSl8t402emIv4M6LQqw==";
$signature = "MEYCIQDu4EP5hG/a4q8OoX7PpTJMHhVHl7ts8Vi/LxFG71pREAIhAP/lp8242qaF9sA5xItLTRDMt33NCudhOAa1FZFK4g26";
$timestamp = 1682594848;

$eventWebHook = new EventWebhook();
$keyToECDSA = $eventWebHook->convertPublicKeyToECDSA($publicKey);
$verifiedSignature = $eventWebHook->verifySignature(
    $keyToECDSA,
    $payload,
    $signature,
    $timestamp
);

echo ($verifiedSignature)
    ? 'Verified'
    : 'Not verified';