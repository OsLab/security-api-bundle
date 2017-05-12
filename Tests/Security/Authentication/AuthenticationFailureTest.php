<?php

/*
 * This file is part of the SecurityApiBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Tests\Security\Authentication;

use OsLab\SecurityApiBundle\Security\Authentication\AuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Unit test for the AuthenticationFailureTest.
 *
 * @author Michael COULLERET <michael.coulleret@gmail.com>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class AuthenticationFailureTest extends \PHPUnit_Framework_TestCase
{
    public function testOnAuthenticationFailure()
    {
        $this->expectException(AccessDeniedHttpException::class);

        $authenticationFailureHandler = new AuthenticationFailureHandler();
        $authenticationFailureHandler->onAuthenticationFailure(new Request(), new AuthenticationException());
    }
}
