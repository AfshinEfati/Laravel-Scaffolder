<?php

namespace Efati\ModuleGenerator\Support;

/**
 * @deprecated Since 1.0.0, use Goli instead. This class is maintained for backward compatibility only.
 *
 * Verta is now a simple wrapper/alias for Goli to consolidate Jalali date handling.
 * All functionality is provided by the Goli class.
 *
 * Example migration:
 * ```php
 * // Old way (deprecated)
 * $date = new Verta('1402-01-01');
 *
 * // New way
 * $date = new Goli('1402-01-01');
 * // Or use the helper
 * $date = goli('1402-01-01');
 * ```
 */
class Verta extends Goli
{
    public function toJalaliDateString(bool $convertNumbers = false): string
    {
        return $this->toGoliDateString($convertNumbers);
    }

    public function toJalaliDateTimeString(bool $convertNumbers = false): string
    {
        return $this->toGoliDateTimeString($convertNumbers);
    }

    public function toJalaliArray(bool $withTime = false): array
    {
        return $this->toGoliArray($withTime);
    }

    public static function jalaliToGregorian(int $year, int $month, int $day): array
    {
        return static::goliToGregorian($year, $month, $day);
    }

    public static function gregorianToJalali(int $year, int $month, int $day): array
    {
        return static::gregorianToGoli($year, $month, $day);
    }

    public static function isLeapJalaliYear(int $year): bool
    {
        return static::isLeapGoliYear($year);
    }
}
