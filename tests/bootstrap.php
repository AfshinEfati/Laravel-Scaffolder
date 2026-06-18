<?php

$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
}

if (!class_exists(\Carbon\Carbon::class)) {
    require __DIR__ . '/Stubs/CarbonStub.php';
}
