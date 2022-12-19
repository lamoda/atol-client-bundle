<?php

namespace Lamoda\AtolClientBundle\Tests;

use Lamoda\AtolClient\V3\AtolApi as AtolApiV3;
use Lamoda\AtolClient\V4\AtolApi as AtolApiV4;
use Lamoda\AtolClient\V5\AtolApi as AtolApiV5;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AtolClientBundleTest extends WebTestCase
{
    /**
     * @dataProvider dataEnv
     */
    public function testAtolApiService(string $env, array $existedServices): void
    {
        $kernel = $this->bootKernel([
            'environment' => $env,
        ]);

        foreach ($existedServices as $name => $class) {
            $this->assertTrue($kernel->getContainer()->has($name));
            $service = $kernel->getContainer()->get($name);
            $this->assertInstanceOf($class, $service);
        }
    }

    public function dataEnv(): array
    {
        return [
            [
                'single_client_v3',
                [
                    'test.atol_client.v3' => AtolApiV3::class,
                ],
            ],
            [
                'single_client_v4',
                [
                    'test.atol_client.v4' => AtolApiV4::class,
                ],
            ],
            [
                'single_client_v5',
                [
                    'test.atol_client.v5' => AtolApiV5::class,
                ],
            ],
            [
                'multiple_clients',
                [
                    'test.atol_client.v3' => AtolApiV3::class,
                    'test.atol_client.v3.second' => AtolApiV3::class,
                    'test.atol_client.v4.third' => AtolApiV4::class,
                    'test.atol_client.v5.fourth' => AtolApiV5::class,
                ],
            ],
        ];
    }
}
