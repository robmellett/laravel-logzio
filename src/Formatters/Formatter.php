<?php

namespace RobMellett\Logzio\Formatters;

use DateTimeInterface;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;

final class Formatter extends JsonFormatter implements FormatterInterface
{
    /**
     * Datetime format for Logz.io
     * @see https://support.logz.io/hc/en-us/articles/210206885
     */
    protected const DATETIME_FORMAT = 'c';

    /**
     * @param  int  $batchMode
     * @param  bool $appendNewline
     */
    public function __construct(int $batchMode = self::BATCH_MODE_NEWLINES, bool $appendNewline = true)
    {
        parent::__construct($batchMode, $appendNewline);
    }

    /**
     * Appends the '@timestamp' parameter for Logz.io.
     *
     * @param  array  $record
     * @return string
     */
    public function format(array $record): string
    {
        if (isset($record["datetime"]) && ($record["datetime"] instanceof DateTimeInterface)) {
            $record["@timestamp"] = $record["datetime"]->format(self::DATETIME_FORMAT);

            unset($record["datetime"]);
        }

        $message = json_decode($record['message']);

        $record['id'] = $message->id;
        $record['type'] = $message->type;
        $record['headers'] = $message->headers;

        if ($message->type == 'Request') {
            $record['method'] = $message->method;
            $record['uri'] = $message?->uri;
            $record['parameters'] = $message?->parameters;
        }

        if ($message->type == 'Response') {
            $record['body'] = $message->body;
            $record['status_code'] = $message->status_code;
        }

        return parent::format($record);
    }
}
