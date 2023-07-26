<?php

namespace App\Tests\Traits\Service;

use App\Enum\HealthStatus;

trait ServiceDataProviderTrait
{

    public function dinoNameProvider(): \Generator
    {
        yield 'Sick Dino' => [
            HealthStatus::SICK,
            'Daisy',
        ];
        yield 'Healthy Dino' => [
            HealthStatus::HEALTHY,
            'Maverick',
        ];
    }

}