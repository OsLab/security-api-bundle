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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This class validates configuration.
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('oslab_security_api');
        $this->addAuthenticationSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds the config of soap to global config.
     *
     * @param ArrayNodeDefinition $node The root element for the config nodes.
     *
     * @return void
     */
    protected function addAuthenticationSection(ArrayNodeDefinition $node)
    {
        $node->children()
            ->arrayNode('authentication')
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('method')
                    ->defaultValue('query')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                    ->ifNotInArray(array('header', 'query'))
                        ->thenInvalid('Invalid method for security %s')
                    ->end()
                ->end()
                ->scalarNode('key_name')->defaultValue('key')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ->end();
    }
}
