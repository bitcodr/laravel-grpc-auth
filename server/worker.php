<?php

use App\Grpc\Interfaces\ServiceInvoker;
use App\Grpc\LaravelServiceInvoker;
use ProtocolBuffer\Auth\AuthServiceInterface;
use App\Grpc\Interfaces\Kernel as kernelInterface;
use App\Grpc\Kernel;
use Spiral\RoadRunner\Worker;
use Spiral\Goridge\StreamRelay;

ini_set('display_errors', 'stderr');

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->singleton(
    kernelInterface::class,
    Kernel::class
);

$app->singleton(
    ServiceInvoker::class,
    LaravelServiceInvoker::class
);

$kernel = $app->make(kernelInterface::class);

$kernel->registerService(AuthServiceInterface::class);

$w = new Worker(new StreamRelay(STDIN, STDOUT));

$kernel->serve($w);