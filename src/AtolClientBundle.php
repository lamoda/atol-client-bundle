<?php

namespace Lamoda\AtolClientBundle;

use Lamoda\AtolClientBundle\DependencyInjection\AtolClientExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AtolClientBundle extends Bundle
{
    public const API_CLIENT_VERSION_3 = 3;
    public const API_CLIENT_VERSION_4 = 4;

    public function getContainerExtension()
    {
        return new AtolClientExtension();
    }
}
