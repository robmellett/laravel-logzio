<?php

namespace RobMellett\Logzio\Formatters;

use GuzzleHttp\MessageFormatter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ResponseMessageFormatter extends MessageFormatter
{
    public function format(
        RequestInterface $request,
        ?ResponseInterface $response = null,
        ?Throwable $error = null
    ): string {
        return json_encode([
            "id" => parent::format($request, $response, $error),
            "type" => "Response",
            "status_code" => $response->getStatusCode(),
            "headers" => $response->getHeaders(),
            "body" => $response->getBody()->__toString()
        ]);
    }
}
