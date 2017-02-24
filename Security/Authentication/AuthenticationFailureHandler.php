<?php

/*
 * This file is part of the OsLabSecurityApiBundle package.
 *
 * (c) OsLab <https://github.com/OsLab>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Security\Authentication;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthenticationFailureHandler
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new AccessDeniedHttpException($exception);
    }
}
