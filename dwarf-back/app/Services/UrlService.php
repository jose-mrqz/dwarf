<?php

namespace App\Services;

use App\Models\Url;

class UrlService
{
    private const BASE62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const MAX_CODE_LENGTH = 8;

    public function shorten(string $url): string
    {
        // md5 hashing to get a 32-character string
        $hash = substr(md5($url), 0, 10); // Reduced to fit 8 chars limit
        $decimal = hexdec($hash);
        // base62 encoding to get a url safe code
        $code = $this->base62Encode($decimal);
        // Ensure that code is less than or equal to 8 chars
        $code = substr($code, 0, self::MAX_CODE_LENGTH);
        $code = $this->resolveCollision($code, $url, $decimal);
        return $code;
    }

    private function base62Encode(int $num): string
    {
        $base = strlen(self::BASE62);
        $result = '';

        // division of the hex value by the base (62) and appending the remainder to result
        while ($num > 0) {
            $result = self::BASE62[$num % $base] . $result;
            $num = (int) ($num / $base);
        }

        return $result ?: '0';
    }

    private function resolveCollision(string $code, string $url, int $decimal): string
    {
        // if the code already exists, we need to find a new one
        $attempt = 0;
        $currentCode = $code;

        while (Url::where('code', $currentCode)->where('url', '!=', $url)->exists()) {
            $attempt++;
            $newDecimal = $decimal + $attempt; // Increment decimal value to avoid collision
            $currentCode = $this->base62Encode($newDecimal);
        }

        return $currentCode;
    }

    public function getUrlByCode(string $code): ?Url
    {
        return Url::where('code', $code)->first();
    }
}