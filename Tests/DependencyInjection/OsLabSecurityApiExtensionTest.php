<?php

/*
 * This file is part of the SecurityApiBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Tests\User;

use OsLab\SecurityApiBundle\DependencyInjection\OsLabSecurityApiExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Unit test for the OsLabSecurityApiExtensionTest.
 *
 * @author Michael COULLERET <michael.coulleret@gmail.com>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class OsLabSecurityApiExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigLoad()
    {
        $extension = new OsLabSecurityApiExtension();
        $container = new ContainerBuilder();

        $extension->load([], $container);

        $this->assertTrue($container->hasDefinition('oslab_security_api.security.authentication.authenticator'));
    }
}
