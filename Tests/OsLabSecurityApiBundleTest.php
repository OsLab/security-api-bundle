<?php

/*
 * This file is part of the SecurityApiBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Tests\Security\Authentication;

use OsLab\SecurityApiBundle\OsLabSecurityApiBundle;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class OsLabSecurityApiBundleTest
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class OsLabSecurityApiBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->registerExtension(new SecurityExtension());

        $osLabSecurityApiBundle = new OsLabSecurityApiBundle();
        $osLabSecurityApiBundle->build($containerBuilder);

        $this->assertInstanceOf(SecurityExtension::class, $containerBuilder->getExtension('security'));
    }
}
