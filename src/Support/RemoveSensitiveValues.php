<?php

namespace RobMellett\Logzio\Support;

use Illuminate\Support\Str;

class RemoveSensitiveValues
{
    private static array $sensitiveValuesToIgnore = [];

    public function __construct()
    {
        self::$sensitiveValuesToIgnore = $this->sensitiveKeysToRemove();
    }

    public static function format(string $message): string
    {
        foreach (self::$sensitiveValuesToIgnore as $secureValue) {
            $message = Str::of($message)->replace($secureValue, '********');
        }

        $regexes = [
            ['find' => '(\"new_password\":\".+\")', 'replace' => '"new_password":"********"'],
            ['find' => '(PasswordDigest=\"[A-Za-z0-9=]+\")', 'replace' => 'PasswordDigest="********"'],
            ['find' => '(Bearer\s([A-Za-z0-9\.\_\-]+))', 'replace' => "Bearer ********"],
        ];

        // Special Scenarios to remove
        foreach ($regexes as $regex) {
            $result = preg_replace($regex['find'], $regex['replace'], $message);
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
            ->map(function ($items) {
                $flattened = [];
                array_walk_recursive($items, function ($item, $key) use (&$flattened) {
                    $flattened += [$key => $item];
                });

                return collect($flattened);
            })
            ->filter(function ($value, $key) use ($searchFor) {
                return Str::of($key)->lower()->contains($searchFor);
            })
            ->values()
            ->filter()
            ->toArray();
    }
}
