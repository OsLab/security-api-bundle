<?php

/*
 * This file is part of the SecurityApiBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Tests\User;

use OsLab\SecurityApiBundle\DependencyInjection\Security\UserProvider\InMemoryApiFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class InMemoryApiFactoryTest
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class InMemoryApiFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $users = [
        'users' => [
            'user' => [
                'password' => 'abc',
                'roles'    => ['ROLE_USER'],

            ],
        ],
    ];

    public function testCreate()
    {
        $container = new ContainerBuilder();
        $inMemoryApiFactory = new InMemoryApiFactory();

        $inMemoryApiFactory->create($container, 'oslab', $this->users);

        $this->assertTrue(($container->hasDefinition('oslab') ?: $container->hasAlias('oslab')));
    }

    public function testKey()
    {
        $inMemoryApiFactory = new InMemoryApiFactory();

        $key = $inMemoryApiFactory->getKey();

        $this->assertEquals($key, 'memory_api');
    }

    public function testAddConfiguration()
    {
        $inMemoryApiFactory = new InMemoryApiFactory();
        $nodeDefinition = new ArrayNodeDefinition('oslab');

        $inMemoryApiFactory->addConfiguration($nodeDefinition);

        $this->assertArrayHasKey('users', $nodeDefinition->getNode()->finalize(['users']));
    }
}
