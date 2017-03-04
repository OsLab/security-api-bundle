<?php

/*
 * This file is part of the SecurityApiBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Tests\Security\Authentication;

use OsLab\SecurityApiBundle\Security\Authentication\SimplePreAuthenticator;
use OsLab\SecurityApiBundle\Security\User\InMemoryApiUserProvider;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class SimplePreAuthenticatorTest
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class SimplePreAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    protected $users;

    public function setUp()
    {
        $this->users = [
            'user' => new User('abc', 'def'),
        ];
    }

    public function testCreateWithInvalidKeyNameTokenAccessDeniedException()
    {
        $this->expectException(AccessDeniedException::class);

        $simplePreAuthenticator = new SimplePreAuthenticator('keyName', 'POST');
        $simplePreAuthenticator->createToken(new Request(), 'providerKey');
    }

    public function testCreateWithHeaderKey()
    {
        $request = new Request();
        $headerBag = new HeaderBag();
        $headerBag->add(['keyName' => 'abcd']);
        $request->headers = $headerBag;

        $simplePreAuthenticator = new SimplePreAuthenticator('keyName', 'header');
        $simplePreAuthenticator->createToken($request, $this->anything());

        $this->isInstanceOf(SimplePreAuthenticator::class);
    }

    public function testCreateWithQueryKey()
    {
        $request = new Request();
        $parameterBag = new ParameterBag();
        $parameterBag->add(['keyName' => 'abcd']);
        $request->query = $parameterBag;

        $simplePreAuthenticator = new SimplePreAuthenticator('keyName', 'query');
        $simplePreAuthenticator->createToken($request, $this->anything());

        $this->isInstanceOf(SimplePreAuthenticator::class);
    }

    public function testSupportsTokenNotIsValid()
    {
        $token = new PreAuthenticatedToken('user', 'credentials', 'xxxx');

        $simplePreAuthenticator = new SimplePreAuthenticator('keyName', 'header');
        $supportsToken = $simplePreAuthenticator->supportsToken($token, 'abcd');

        $this->assertFalse($supportsToken);
    }

    public function testSupportsTokenIsValid()
    {
        $token = new PreAuthenticatedToken('user', 'credentials', 'abcd');

        $simplePreAuthenticator = new SimplePreAuthenticator('keyName', 'header');
        $supportsToken = $simplePreAuthenticator->supportsToken($token, 'abcd');

        $this->assertTrue($supportsToken);
    }

    public function testAuthenticateTokenInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $token = new PreAuthenticatedToken('user', 'credentials', 'abcd');
        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->getMock();

        $simplePreAuthenticator = new SimplePreAuthenticator('keyName', 'header');
        $simplePreAuthenticator->authenticateToken($token, $userProvider, 'oslab');
    }

    public function testAuthenticateTokenUsernameNotFoundException()
    {
        $this->expectException(UsernameNotFoundException::class);

        $token = new PreAuthenticatedToken('user', 'credentials', 'abcd');
        $userProvider = new InMemoryApiUserProvider();

        $simplePreAuthenticator = new SimplePreAuthenticator('keyName', 'header');
        $simplePreAuthenticator->authenticateToken($token, $userProvider, 'oslab');
    }

    public function testAuthenticateToken()
    {
        $token = $this->getMockBuilder(PreAuthenticatedToken::class)->disableOriginalConstructor()->getMock();
        $token->expects($this->once())
            ->method('getCredentials')
            ->will($this->returnValue('abc'))
        ;

        $userProvider = $this->getMockBuilder(InMemoryApiUserProvider::class)->getMock();
        $userProvider->expects($this->once())
            ->method('getUsernameByApiKey')
            ->will($this->returnValue('abcdef'))
        ;

        $userProvider->expects($this->once())
            ->method('loadUserByUsername')
            ->will($this->returnValue(new User('abc', 'def')))
        ;

        $simplePreAuthenticator = new SimplePreAuthenticator('keyName', 'header');
        $preAuthenticatedToken = $simplePreAuthenticator->authenticateToken($token, $userProvider, 'oslab');
    }
}
