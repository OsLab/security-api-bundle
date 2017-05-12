<?php

/*
 * This file is part of the SecurityApiBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Tests\User;

use OsLab\SecurityApiBundle\Security\User\InMemoryApiUserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\User;

/**
 * Unit test for the InMemoryApiProviderTest.
 *
 * @author Michael COULLERET <michael.coulleret@gmail.com>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class InMemoryApiProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $userProvider;
    protected $users;

    public function setUp()
    {
        $this->users = [
            'api_1' => new User('abc', '1x4c40nwh96080gk70f7k5awz9k6tczqs3jr01z94849n', ['ROLE_API']),
            'api_2' => new User('cde', 'j6eef2w0689a6if50c365v2zq0c855ywgyt106j2b6q5h', ['ROLE_API']),
        ];

        $this->userProvider = new InMemoryApiUserProvider($this->users);
    }

    public function testGetUsernameByApiKeyWhenIsBadKey()
    {
        $user = $this->userProvider->getUsernameByApiKey('bad_api_key');

        $this->assertNull($user);
    }

    public function testGetUsernameByApiKey()
    {
        $user = $this->userProvider->getUsernameByApiKey('j6eef2w0689a6if50c365v2zq0c855ywgyt106j2b6q5h');

        $this->assertSame($user, 'cde');
    }

    public function testShouldLoadUserByUsername2()
    {
        $this->expectException(UsernameNotFoundException::class);

        $this->userProvider->loadUserByUsername('api_bad');
    }

    public function testShouldLoadUserByUsername()
    {
        $user = $this->userProvider->loadUserByUsername('api_1');

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame($user->getRoles(), ['ROLE_API']);
    }

    public function testCreateUserLogicException()
    {
        $this->expectException(\LogicException::class);

        $user = new User('api_1', '1x4c40nwh96080gk70f7k5awz9k6tczqs3jr01z94849n', ['ROLE_API']);

        $this->userProvider->createUser($user);
    }

    public function testCreateUser()
    {
        $user = $this->getMockBuilder(AdvancedUserInterface::class)->disableOriginalConstructor()->getMock();
        $user->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue('api_3'))
        ;

        $this->userProvider->createUser($user);
    }

    public function testRefreshUserUnsupportedUserException()
    {
        $this->expectException(UnsupportedUserException::class);

        $this->userProvider->refreshUser(new User('abc', 'cde'));
    }

    public function testSupportsClass()
    {
        $supportsClass = $this->userProvider->supportsClass($this->userProvider);

        $this->assertFalse($supportsClass);
    }
}
