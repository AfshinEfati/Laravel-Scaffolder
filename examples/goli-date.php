<?php

declare(strict_types=1);

use Carbon\Carbon;
use Efati\ModuleGenerator\Support\Goli;

require __DIR__ . '/../vendor/autoload.php';

$jalali = goli(Carbon::create(2024, 3, 20, 12, 0, 0, 'UTC'));

if ($jalali->toGoliDateString() !== '1403-01-01') {
    throw new RuntimeException('Unexpected Jalali conversion.');
}

$gregorian = Goli::parseGoli('1403-01-01 12:00:00', 'UTC')->toCarbon();

if ($gregorian->format('Y-m-d H:i:s') !== '2024-03-20 12:00:00') {
    throw new RuntimeException('Unexpected Gregorian conversion.');
}

echo $jalali->format('Y/m/d H:i:s') . PHP_EOL;
echo $gregorian->format('Y-m-d H:i:s') . PHP_EOL;
