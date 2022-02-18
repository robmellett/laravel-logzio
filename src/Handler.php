<?php

namespace RobMellett\Logzio;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use LogicException;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use RobMellett\Logzio\Formatters\Formatter;

class Handler extends AbstractProcessingHandler
{
    /**
     * The HTTP client
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @param  int|string $level   The minimum logging level to trigger this handler.
     * @param  bool       $bubble  Whether or not messages that are handled should bubble up the stack.
     * @param  array      $options Logz.io client options
     * @throws \LogicException If curl extension is not available.
     */
    public function __construct($level = Logger::DEBUG, bool $bubble = true, array $options = [])
    {
        if (empty($options['token'])) {
            throw new LogicException('The token parameter is required to use the Logz.io Handler');
        }

        $this->client = $this->buildHttpClient($options);
        $this->endpoint = $this->buildEndpoint($options);

        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritdoc}
     */
    public function handleBatch(array $records): void
    {
        $processed = [];

        foreach ($records as $record) {
            if ($this->isHandling($record)) {
                $processed[] = $this->processRecord($record);
            }
        }

        if (! empty($processed)) {
            $this->send($this->getFormatter()->formatBatch($processed));
        }
    }

    /**
     * Build listener endpoint
     *
     * @param  array $options
     * @return string
     */
    protected function buildEndpoint(array $options = []): string
    {
        $region = $options['region'] ?? '';
        $useSsl = $options['ssl'] ?? true;

        $endpoint = sprintf(
            '%s://listener%s.logz.io:%d',
            $useSsl ? 'https' : 'http',
            $this->validRegion($region) ? "-$region" : '',
            $useSsl ? 8071 : 8070
        );

        $endpoint .= '?' . http_build_query(array_filter([
            'token' => $options['token'] ?? null,
            'type' => $options['type'] ?? null,
        ]));

        return $endpoint;
    }

    /**
     * Build HTTP client
     *
     * @param  array $options
     * @return ClientInterface
     */
    protected function buildHttpClient(array $options): ClientInterface
    {
        if (isset($options['http_client']) && $options['http_client'] instanceof ClientInterface) {
            return $options['http_client'];
        }

        return new Client([
            'headers' => [
                'User-Agent' => 'laravel-logzio',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new Formatter();
    }

    /**
     * Send logging data to server
     *
     * @param mixed $data
     * @return void
     */
    protected function send($data)
    {
        $headers = ['Content-Type: application/json'];
        $request = new Request('POST', $this->endpoint, $headers, $data);

        $this->client->send($request);
    }

    /**
     * Validate region
     *
     * @param  string $region
     * @return bool
     */
    protected function validRegion(string $region): bool
    {
        return in_array($region, [
            'au', // Asia Pacific (Sydney) AWS
            'ca', // Canada (Central) AWS
            'eu', // Europe (Frankfurt) AWS
            'nl', // West Europe (Netherlands) Azure
            'wa', // West US 2 (Washington) Azure
            'uk', // EU West (AWS)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        $this->send($record['formatted']);
    }
}
