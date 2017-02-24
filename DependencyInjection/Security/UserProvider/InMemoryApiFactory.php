<?php

/*
 * This file is part of the OsLabSecurityApiBundle package.
 *
 * (c) OsLab <https://github.com/OsLab>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\DependencyInjection\Security\UserProvider;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * InMemoryApiFactory creates services for the memory api provider.
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class InMemoryApiFactory implements UserProviderFactoryInterface
{
    /**
     * Creates the userProvider.
     *
     * @param ContainerBuilder $container Instance of container.
     * @param string           $id        The service id for concrete user provider.
     * @param array            $config    Configuration of current factory.
     */
    public function create(ContainerBuilder $container, $id, $config)
    {
        $definition = $container->setDefinition($id, new DefinitionDecorator('oslab_security_api.security.user.provider'));

        foreach ($config['users'] as $username => $user) {
            $userId = $id.'_'.$username;

            $container
                ->setDefinition($userId, new DefinitionDecorator('security.user.provider.in_memory.user'))
                ->setArguments(array($username, (string) $user['password'], $user['roles']))
            ;

            $definition->addMethodCall('createUser', array(new Reference($userId)));
        }
    }

    /**
     * Returns the key of current factory.
     *
     * @return string
     */
    public function getKey()
    {
        return 'memory_api';
    }

    /**
     * Adds configuration nodes to current user provider.
     *
     * @param NodeDefinition $node
     */
    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->fixXmlConfig('user')
            ->children()
                ->arrayNode('users')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('password')->defaultValue(uniqid('', true))->end()
                            ->arrayNode('roles')
                                ->beforeNormalization()->ifString()->then(function ($v) {
                                    return preg_split('/\s*,\s*/', $v);
                                })
                                ->end()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
