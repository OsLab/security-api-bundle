<?php

/*
 * This file is part of the SecurityApiBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Tests\User;

use OsLab\SecurityApiBundle\Security\User\InMemoryApiUserProvider;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class InMemoryApiProviderTest
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class InMemoryApiProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $userProvider;
    protected $apiKeyUsers;
    protected $apiKey;
    protected $username;
    protected $user = [
        'api_1' => 'abcd',
        'api_2' => 'efgh',
    ];

    public function testShouldThrowExceptionWhenUserNotFound()
    {
        $this->expectException(UsernameNotFoundException::class);

        $userProvider = new InMemoryApiUserProvider($this->user);
        $userProvider->loadUserByUsername('efgh');
    }
}
