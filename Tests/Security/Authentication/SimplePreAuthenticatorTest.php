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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\InMemoryApiUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class SimplePreAuthenticatorTest
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class SimplePreAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
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
}
