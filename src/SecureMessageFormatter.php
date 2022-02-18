<?php

namespace RobMellett\Logzio;

use GuzzleHttp\MessageFormatter;
use Illuminate\Support\Str;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class SecureMessageFormatter extends MessageFormatter
{
    /**
     * Sensitive Values that will be hidden from logging
     * @var array
     */
    private static array $sensitiveValuesToIgnore = [];

    /**
     * SecureMessageFormatter constructor.
     * @param string|null $template
     */
    public function __construct(?string $template = self::CLF)
    {
        parent::__construct($template);

        self::$sensitiveValuesToIgnore = $this->sensitiveKeysToRemove();
    }

    public function format(
        RequestInterface $request,
        ?ResponseInterface $response = null,
        ?Throwable $error = null
    ): string {
        $result = parent::format($request, $response, $error);

        return $this->replaceSensitiveValues($result);
    }

    /**
     * @param string $result
     */
    private function replaceSensitiveValues(string $result)
    {
        foreach (self::$sensitiveValuesToIgnore as $secureValue) {
            $result = Str::of($result)->replace($secureValue, '********');
        }

        $regexes = [
            ['find' => '(\"new_password\":\".+\")', 'replace' => '"new_password":"********"'],
            ['find' => '(PasswordDigest=\"[A-Za-z0-9=]+\")', 'replace' => 'PasswordDigest="********"']
        ];

        // Special Scenarios to remove
        foreach ($regexes as $regex) {
            $result = preg_replace($regex['find'], $regex['replace'], $result);
        }

        return $result;
    }

    /**
     * Select all the service keys that contain sensitive tokens, api keys etc
     * @return mixed
     */
    private function sensitiveKeysToRemove()
    {
        $searchFor = ['key', 'secret', 'hash', 'token'];

        return collect(config('services'))
            ->flattenWithKeys()
            ->filter(function ($value, $key) use ($searchFor) {
                return Str::of($key)->lower()->contains($searchFor);
            })
            ->values()
            ->filter()
            ->toArray();
    }
}
