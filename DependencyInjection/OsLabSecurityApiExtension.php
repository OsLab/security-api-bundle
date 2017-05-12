<?php

/*
 * This file is part of the OsLabSecurityApiBundle package.
 *
 * (c) OsLab <https://github.com/OsLab>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class load bundle extension.
 *
 * @author Michael COULLERET <michael.coulleret@gmail.com>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class OsLabSecurityApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('security.yml');

        $this->remapParametersNamespaces($config, $container, ['authentication' => 'oslab_security_api.authentication.%s']);
    }

    /**
     * Maps parameters to add them in container.
     *
     * @param array            $config     the global config of this bundle
     * @param ContainerBuilder $container  the container for dependency injection
     * @param array            $namespaces config namespaces to add as parameters in the container
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $namespace => $map) {
            if (isset($namespace) && strlen($namespace) > 0) {
                if (!array_key_exists($namespace, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$namespace];
            } else {
                $namespaceConfig = $config;
            }

            foreach ($namespaceConfig as $name => $value) {
                $container->setParameter(sprintf($map, $name), $value);
            }
        }
    }
}
