<?php

namespace RobMellett\Logzio\Formatters;

use GuzzleHttp\MessageFormatter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class RequestMessageFormatter extends MessageFormatter
{
    public function format(
        RequestInterface $request,
        ?ResponseInterface $response = null,
        ?Throwable $error = null
    ): string {
        return json_encode([
            "id" => parent::format($request, $response, $error),
            "type" => "Request",
            "method" => $request->getMethod(),
            "uri" => $request->getUri()->__toString(),
            "headers" => $request->getHeaders(),
            "body" => $request->getBody()->__toString()
        ]);
    }
}
