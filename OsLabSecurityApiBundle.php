<?php

/*
 * This file is part of the SecurityApiBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle;

use OsLab\SecurityApiBundle\DependencyInjection\Security\UserProvider\InMemoryApiFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * An implementation of BundleInterface.
 *
 * @author Michael COULLERET <michael.coulleret@gmail.com>
 */
class OsLabSecurityApiBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');

        $extension->addUserProviderFactory(new InMemoryApiFactory());
    }
}
