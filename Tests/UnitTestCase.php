<?php

namespace Dbt\Timeline\Tests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class UnitTestCase extends TestCase
{
    public function now (): Carbon
    {
        static $now = null;

        if (!$now) {
            $now = Carbon::now();
        }

        return $now->copy();
    }

    public static function rs (int $chars): string
    {
        $string = '';

        while (($len = strlen($string)) < $chars) {
            $size = $chars - $len;

            $bytes = random_bytes($size);

            $string .= substr(
                str_replace(['/', '+', '='], '', base64_encode($bytes)),
                0,
                $size
            );
        }

        return $string;
    }

    public static function ri (int $min, int $max): int
    {
        return rand($min, $max);
    }
}
